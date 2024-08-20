<?php

namespace App\Exports;

use App\Models\Report;
use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Transaction::with('product')->get()->map(function ($transaction) {
            return [
                'Product' => $transaction->product->name,
                'Quantity' => $transaction->quantity,
                'Date' => $transaction->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Product',
            'Quantity',
            'Price',
            'Date',
        ];
    }
}