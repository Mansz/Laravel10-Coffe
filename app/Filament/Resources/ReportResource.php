<?php

namespace App\Filament\Resources;

use App\Exports\ReportsExport;
use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\TextColumn::make('quantity')->label('Quantity'),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime()
                    ->getStateUsing(fn ($record) => Carbon::parse($record->created_at)
                    ->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')),
            ])
            ->actions([
                Tables\Actions\Action::make('Export to Excel')
                    ->action(fn () => Excel::download(new ReportsExport, 'Data reports.xlsx'))
                    ->icon('heroicon-o-arrow-down'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}