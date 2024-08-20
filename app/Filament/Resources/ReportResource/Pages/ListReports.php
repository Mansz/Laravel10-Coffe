<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\Transaction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('product.name')->label('Product'),
            Tables\Columns\TextColumn::make('quantity')->label('Quantity'),
            Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime()
                ->getStateUsing(fn ($record) => Carbon::parse($record->created_at)
                ->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('export')
                ->label('Export to Excel')
                ->action('generateReport')
                ->icon('heroicon-o-download'),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        return Transaction::with('product');
    }

    public function generateReport()
    {
        return Excel::download(new SalesReportExport, 'sales_report_' . now()->format('Y_m_d_H_i_s') . '.xlsx');
    }
}