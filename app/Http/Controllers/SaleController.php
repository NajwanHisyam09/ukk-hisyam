<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('search') && $request->search !== null) {
            $search = strtolower($request->search);
            $sales = Sale::whereRaw('LOWER(invoice_number) LIKE ?', ['%' . $search . '%'])
                ->paginate(10)
                ->appends($request->only('search'));
        } else {
            $sales = Sale::latest()->paginate(10);
        }

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::all();
        $members = Member::all();
        return view('sales.create', compact('products', 'members'));
    }

    public function confirmationStore(Request $request)
    {
        $filteredQuantities = [];

        foreach ($request->input('quantities', []) as $key => $value) {
            if ($value > 0) {
                $filteredQuantities[$key] = $value;
            }
        }

        $products = Product::whereIn('id', array_keys($filteredQuantities))->get();
        $totalAmount = $products->sum(function ($product) use ($filteredQuantities) {
            return $product->price * $filteredQuantities[$product->id];
        });

        $members = Member::all();

        return view('sales.confirmation', compact('products', 'totalAmount', 'members', 'filteredQuantities'));
    }

    public function store(Request $request)
{

    $request->validate([
        'total_pay' => ['required', 'numeric', 'min:' . $request->total_amount],
    ], [
        'total_pay.min' => 'Jumlah bayar tidak boleh kurang dari total pembayaran.'
    ]);
    
    $productData = json_decode($request->input('product_data'), true);
    $totalPay = $request->input('total_pay');
    $totalAmount = $request->input('total_amount');
    $invoiceNumber = 'INV-' . strtoupper(Str::random(8));

    $memberName = $invoiceNumber;
    $memberId = null;
    $member = null;

    if (!empty($request->member_id)) {
        $member = Member::find($request->member_id);
        if ($member) {
            $memberId = $member->id;
            $memberName = $member->name;
        }
    }

    // Redirect ke view khusus jika hanya ingin lihat ringkasan member
    if ($request->is_member == 'yes') {
        $products = $productData;
        return view('sales.member', compact('member', 'products', 'totalAmount', 'totalPay'));
    }

    // Point logic
    $discount = 0;
    $addPoint = 0;
    $currentPoints = 0;

    if ($request->use_point == 1) {
        $totalAmount -= $request->total_point;
        Member::where('id', $memberId)->decrement('points', $request->total_point);
        $discount = $request->total_point;
    } else {
        if ($memberId) {
            $addPoint = floor($totalAmount / 100); // misal setiap 750 dapat 1 poin
            Member::where('id', $memberId)->increment('points', $addPoint);
        }
    }

    // Get current point setelah perubahan
    if ($memberId) {
        $currentPoints = Member::find($memberId)->points;
    }

    // Simpan transaksi
    Sale::create([
        'id' => Str::uuid(),
        'invoice_number' => $invoiceNumber,
        'customer_name' => $memberName,
        'user_id' => Auth::user()->id,
        'member_id' => $memberId,
        'product_data' => json_encode($productData),
        'total_amount' => $totalAmount,
        'payment_amount' => $totalPay,
        'change_amount' => $totalPay - $totalAmount,
        'notes' => '-',
    ]);

    // Update stok produk
    foreach ($productData as $product) {
        Product::where('id', $product['id'])->decrement('quantity', $product['quantity']);
    }

    // Kirim data ke invoice view
    return view('sales.invoice', compact(
        'invoiceNumber',
        'totalAmount',
        'totalPay',
        'memberName',
        'memberId',
        'productData',
        'discount',
        'addPoint',
        'currentPoints'
    ));
}

    public function showInvoice($id)
    {
        $sale = Sale::where('id', $id)->firstOrFail();

        $productData = json_decode($sale->product_data, true);

        $totalProductPrice = array_reduce($productData, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $discount = $totalProductPrice - $sale->total_amount;

        return view('sales.invoice-detail', [
            'invoiceNumber' => $sale->invoice_number,
            'memberName'    => $sale->customer_name,
            'memberId'      => $sale->member_id,
            'productData'   => $productData,
            'totalAmount'   => $sale->total_amount,
            'totalPay'      => $sale->payment_amount,
            'changeAmount'  => $sale->change_amount,
            'discount'      => $discount,
            'createdAt'     => $sale->created_at
        ]);
    }


    public function show(Sale $sale)
    {
        $sale->product_data = json_decode($sale->product_data, true);
        return view('sales.show', compact('sale'));
    }
}
