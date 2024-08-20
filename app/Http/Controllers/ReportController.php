<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ]);

        Report::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Product added successfully.']);
    }

    public function sellProduct(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $requestedQuantity = $request->quantity;

        $reports = Report::where('product_id', $productId)
            ->orderBy('created_at') // Urutkan berdasarkan tanggal masuk
            ->get();

        $remainingQuantity = $requestedQuantity;

        foreach ($reports as $report) {
            if ($report->quantity >= $remainingQuantity) {
                // Jika cukup, kurangi dan selesai
                $report->quantity -= $remainingQuantity;
                $report->save();
                return response()->json(['message' => 'Stock updated successfully.']);
            } else {
                // Jika tidak cukup, kurangi semua dan lanjut ke laporan berikutnya
                $remainingQuantity -= $report->quantity;
                $report->quantity = 0;
                $report->save();
            }
        }

        // Jika masih ada sisa quantity yang diminta
        if ($remainingQuantity > 0) {
            return response()->json(['message' => 'Not enough stock available.'], 400);
        }
    }
}