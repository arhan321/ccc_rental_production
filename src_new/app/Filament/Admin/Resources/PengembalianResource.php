<?php

namespace App\Filament\Admin\Resources;

use App\Models\Order;
use App\Models\Pengembalian;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\PengembalianResource\Pages;
use Filament\Forms\Components\{Card, DatePicker, Select, TextInput};

class PengembalianResource extends Resource
{
    /* ============================================================= */
    /*  Konfigurasi dasar                                             */
    /* ============================================================= */
    protected static ?string $model            = Pengembalian::class;
    protected static ?string $navigationLabel  = 'Pengembalian';
    protected static ?string $navigationGroup  = 'Order Management';
    protected static ?string $navigationIcon   = 'heroicon-o-rectangle-stack';

    /* ============================================================= */
    /*  FORM                                                         */
    /* ============================================================= */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make()
                ->schema([
                    /* ----------- Order (hanya yg belum ada pengembalian) --------- */
                    Select::make('order_id')
                        ->label('Order')
                        ->options(fn () =>
                            Order::where('tanggal_batas_sewa', '<=', now())
                                 ->whereDoesntHave('pengembalian')      // belum pernah dikembalikan
                                 ->pluck('nomor_pesanan', 'id')
                        )
                        ->searchable()
                        ->required()
                        ->columnSpan(2),

                    /* ----------- Tanggal Pengembalian ---------- */
                    DatePicker::make('tanggal_pengembalian')
                        ->label('Tanggal Pengembalian')
                        ->required()
                        ->columnSpan(2),

                    /* ----------- Status ---------- */
                    Select::make('status')
                        ->options([
                            'Perlu Dikembalikan' => 'Perlu Dikembalikan',
                            'Terlambat'          => 'Terlambat',
                            'Dikembalikan'       => 'Dikembalikan',
                        ])
                        ->default('Perlu Dikembalikan')
                        ->required(),

                    /* ----------- Denda (opsional) ---------- */
                    TextInput::make('denda')
                        ->label('Denda (IDR)')
                        ->numeric()
                        ->prefix('Rp')
                        ->minValue(0)
                        ->default(0)
                        ->columnSpan(2),
                ])
                ->columns(2),
        ]);
    }

    /* ============================================================= */
    /*  TABLE                                                        */
    /* ============================================================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                /*  Nomor order  */
                Tables\Columns\TextColumn::make('order.nomor_pesanan')
                    ->label('Order')
                    ->sortable()
                    ->searchable(),

                /*  Deadline  */
                Tables\Columns\TextColumn::make('order.tanggal_batas_sewa')
                    ->label('Deadline')
                    ->date()
                    ->sortable(),

                /*  Tanggal kembali  */
                Tables\Columns\TextColumn::make('tanggal_pengembalian')
                    ->label('Tanggal Kembali')
                    ->date()
                    ->sortable(),

                /*  Status  */
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'Perlu Dikembalikan',
                        'danger'    => 'Terlambat',
                        'success'   => 'Dikembalikan',
                    ])
                    ->label('Status'),

                /*  Denda  */
                Tables\Columns\TextColumn::make('denda')
                    ->money('IDR', true)
                    ->label('Denda'),
            ])
            ->defaultSort('tanggal_pengembalian', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /* ============================================================= */
    /*  Halaman bawaan                                               */
    /* ============================================================= */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPengembalians::route('/'),
            'create' => Pages\CreatePengembalian::route('/create'),
            'edit'   => Pages\EditPengembalian::route('/{record}/edit'),
        ];
    }
}
