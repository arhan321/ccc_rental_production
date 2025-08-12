<?php

namespace App\Filament\Admin\Resources;

use App\Models\OrderItem;
use App\Models\Kostum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\{Select, TextInput};
use App\Filament\Admin\Resources\OrderItemResource\Pages;

class OrderItemResource extends Resource
{
    protected static ?string $model = OrderItem::class;
    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Order Item';
    protected static ?string $navigationGroup = 'Order Management';

    /* ------------------------------------------------------------------ */
    /*  FORM                                                              */
    /* ------------------------------------------------------------------ */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make([
                Select::make('order_id')
                    ->label('Order')
                    ->relationship('order', 'nomor_pesanan')
                    ->required()
                    ->columnSpan(2),

                Select::make('kostums_id')
                    ->label('Kostum')
                    ->relationship('kostums', 'nama_kostum')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, $set) =>
                        $set('harga_sewa', Kostum::find($state)?->harga ?? 0)
                    )
                    ->afterStateHydrated(fn ($set, $get) =>
                        $set('harga_sewa', Kostum::find($get('kostums_id'))?->harga ?? 0)
                    )
                    ->columnSpan(2),

                Select::make('ukuran')
                    ->label('Ukuran')
                    ->options(['S'=>'S','M'=>'M','L'=>'L','XL'=>'XL'])
                    ->required(),

                TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->required(),

                TextInput::make('harga_sewa')
                    ->label('Harga Sewa')
                    ->numeric()
                    ->readOnly()
                    ->required()
                    ->dehydrated(),
            ])->columns(2),
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  TABLE                                                             */
    /* ------------------------------------------------------------------ */
    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('order.nomor_pesanan')->label('Order'),
            Tables\Columns\TextColumn::make('kostums.nama_kostum')->label('Kostum'),
            Tables\Columns\TextColumn::make('ukuran')->label('Ukuran'),
            Tables\Columns\TextColumn::make('order.durasi_sewa')->label('Durasi (hari)'),
            Tables\Columns\TextColumn::make('harga_sewa')->label('Harga')->money('IDR'),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault:true),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrderItems::route('/'),
            'create' => Pages\CreateOrderItem::route('/create'),
            'edit'   => Pages\EditOrderItem::route('/{record}/edit'),
        ];
    }
}
