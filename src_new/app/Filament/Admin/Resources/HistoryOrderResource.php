<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\HistoryOrderResource\Pages;
use App\Filament\Admin\Resources\HistoryOrderResource\RelationManagers;

class HistoryOrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    // protected static ?string $navigationLabel = 'History Orders';  // Label yang muncul di menu navigasi
    protected static ?string $navigationLabel = 'History Orders';
    protected static ?string $navigationGroup = 'Order Management';
public static function getPluralLabel(): string
{
    return 'History Orders';
}
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
    ->query(Order::query()->where('status', 'Selesai')) // Filter query
    ->columns([
        TextColumn::make('nomor_pesanan')
            ->label('Order ID')
            ->sortable()
            ->searchable()
            ->badge()
            ->color('primary'),

        TextColumn::make('profile.nama_lengkap')
            ->label('Customer Name')
            ->sortable()
            ->searchable()
            ->limit(25),

        TextColumn::make('tanggal_order')
            ->label('Order Date')
            ->sortable()
            ->date()
            ->alignCenter(),

        TextColumn::make('tanggal_batas_sewa')
            ->label('Rental End Date')
            ->sortable()
            ->date()
            ->alignCenter(),

        TextColumn::make('total_harga')
            ->label('Total Price')
            ->money('IDR', true)
            ->sortable()
            ->alignRight(),

        TextColumn::make('status')
            ->label('Status')
            ->badge()
            ->color('success')
            ->alignCenter(),
    ])

    ->filters([
        // Tambahkan filter jika ingin filter tanggal/order/profile
    ])

    ->actions([
        ViewAction::make()
            ->tooltip('Lihat Detail Order')
            ->icon('heroicon-o-eye')
            ->color('indigo')
            ->button()
            ->extraAttributes([
                'class' => 'rounded-md px-3 py-1 text-white bg-indigo-600 hover:bg-indigo-700 transition-all'
            ]),
    ])

    ->bulkActions([
        BulkActionGroup::make([
            DeleteBulkAction::make()
                ->label('Hapus Terpilih')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->extraAttributes([
                    'class' => 'bg-red-500 text-white rounded-md px-3 py-1 hover:bg-red-600 transition-all'
                ]),
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
            'index' => Pages\ListHistoryOrders::route('/'),
            'create' => Pages\CreateHistoryOrder::route('/create'),
            'edit' => Pages\EditHistoryOrder::route('/{record}/edit'),
        ];
    }
}
