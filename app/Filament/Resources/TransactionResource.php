<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Forms\Components\TextInput::make('quantity')->required()->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
    ->label('Product'),
Tables\Columns\TextColumn::make('quantity')
    ->label('Quantity'),
Tables\Columns\TextColumn::make('created_at')
    ->label('Date')
    ->dateTime()
    ->getStateUsing(fn ($record) => Carbon::parse($record->created_at)
        ->setTimezone('Asia/Jakarta')
        ->format('Y-m-d H:i:s')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
    protected static function afterCreate(Transaction $transaction): void
    {
        $product = Product::find($transaction->product_id);
        $quantityToDeduct = $transaction->quantity;

        // Deduct stock using FIFO
        if ($product && $product->stock >= $quantityToDeduct) {
            $product->stock -= $quantityToDeduct;
            $product->save();
        }
    }
}
