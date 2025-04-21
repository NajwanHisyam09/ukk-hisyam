<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Sale;


class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    private static $counter = 1;
    private $maxProducts = 0;

    public function __construct()
    {
        // Hitung jumlah maksimum produk dalam satu penjualan
        $this->maxProducts = Sale::all()->map(function ($sale) {
            $products = is_string($sale->product_data)
                ? json_decode($sale->product_data, true)
                : $sale->product_data;
            return is_array($products) ? count($products) : 0;
        })->max();
    }

    public function collection()
    {
        return Sale::all();
    }

    public function headings(): array
    {
        $headers = [
            'No',
            'Nomor Invoice',
            'Nama Pelanggan',
            'Tanggal Penjualan',
        ];

        // Tambahkan kolom Produk dan Qty sesuai jumlah maksimum produk
        for ($i = 1; $i <= $this->maxProducts; $i++) {
            $headers[] = "Produk $i";
            $headers[] = "Qty $i";
        }

        // Tambahan kolom lain
        $headers = array_merge($headers, [
            'Total Harga',
            'Total Bayar',
            'Kembalian',
            'Diskon',
            'Dibuat Oleh'
        ]);

        return $headers;
    }

    public function map($sale): array
    {
        $row = [];

        $id = self::$counter++;

        $row[] = $id;
        $row[] = $sale->invoice_number;
        $row[] = $sale->customer_name;
        $row[] = $sale->created_at->format('d-m-Y H:i');

        $productData = is_string($sale->product_data) ? json_decode($sale->product_data, true) : $sale->product_data;
        if (!is_array($productData)) {
            $productData = [];
        }

        // Tambahkan Produk dan Qty ke kolom yang sesuai
        for ($i = 0; $i < $this->maxProducts; $i++) {
            if (isset($productData[$i])) {
                $row[] = $productData[$i]['name'] ?? '-';
                $row[] = $productData[$i]['quantity'] ?? '-';
            } else {
                $row[] = '-';
                $row[] = '-';
            }
        }

        $totalProductPrice = array_reduce($productData, function ($carry, $item) {
            return $carry + ((float) $item['price'] * (int) $item['quantity']);
        }, 0);

        $discount = $totalProductPrice - (float) $sale->total_amount;

        $row[] = 'Rp ' . number_format($sale->total_amount, 0, ',', '.');
        $row[] = 'Rp ' . number_format($sale->payment_amount, 0, ',', '.');
        $row[] = 'Rp ' . number_format($sale->change_amount, 0, ',', '.');
        $row[] = 'Rp ' . number_format($discount, 0, ',', '.');
        $row[] = DB::table('users')->where('id', $sale->user_id)->value('name');

        return $row;
    }
}
