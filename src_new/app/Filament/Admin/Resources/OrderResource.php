<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Kostum;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Admin\Resources\OrderResource\Pages;
use Filament\Forms\Components\{DatePicker, Hidden, Repeater, Select, TextInput, Textarea};

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Order';
    protected static ?string $navigationGroup = 'Order Management';

    /* ------------------------------------------------------------------ */
    /*  FORM                                                              */
    /* ------------------------------------------------------------------ */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make([
                TextInput::make('nomor_pesanan')
                    ->label('Nomor Otomatis')
                    ->disabled()
                    ->dehydrated(false),

                Select::make('profile_id')
                    ->label('Profile')
                    ->relationship('profile', 'nama_lengkap')
                    ->required()
                    ->columnSpan(2),

                /* ---------- Kostum via Repeater ---------- */
                Repeater::make('orderItems')
    ->relationship('orderItems')
    ->schema([
        Select::make('kostums_id')
            ->label('Kostum')
            ->relationship('kostums', 'nama_kostum')
            ->searchable()
            ->required()
            ->reactive()
            ->afterStateUpdated(fn ($state, $set) =>
                $set('harga_sewa', \App\Models\Kostum::find($state)?->harga_sewa ?? 0)
            )
            ->afterStateHydrated(fn ($set, $get) =>
                $set('harga_sewa', \App\Models\Kostum::find($get('kostums_id'))?->harga_sewa ?? 0)
            ),

        TextInput::make('durasi_sewa')
            ->label('Durasi (hari)')
            ->numeric()
            ->disabled()
            ->default(fn ($get) => $get('../../durasi_sewa'))
            ->dehydrated(),

        TextInput::make('harga_sewa')
            ->label('Harga Sewa')
            ->numeric()
            ->readOnly()     // hanya tampil, tak bisa diubah
            ->required()     // kolom DB NOT NULL
            ->dehydrated()   // wajib supaya ikut payload
            ->default(0),
    ])
    ->columns(2),

                DatePicker::make('tanggal_order')
                    ->label('Order Date')
                    ->required()
                    ->columnSpan(2),

                DatePicker::make('tanggal_batas_sewa')
                    ->label('Rental End Date')
                    ->required()
                    ->columnSpan(2),

                TextInput::make('total_harga')
                    ->label('Total Harga')
                    ->numeric()
                    ->required()
                    ->columnSpan(2),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Menunggu'      => 'Menunggu',
                        'Diproses'      => 'Diproses',
                        'Siap Di Ambil' => 'Siap Di Ambil',
                        'Selesai'       => 'Selesai',
                    ])
                    ->default('Menunggu')
                    ->required(),

                Textarea::make('alamat_toko')
                    ->label('Alamat Toko')
                    ->required()
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  TABLE                                                             */
    /* ------------------------------------------------------------------ */
    public static function table(Table $table): Table
    {

return $table
    ->columns([
        TextColumn::make('nomor_pesanan')
            ->label('No')
            ->searchable()
            ->sortable()
            ->badge()
            ->alignCenter(),

        TextColumn::make('profile.nama_lengkap')
            ->label('Profile')
            ->searchable()
            ->sortable()
            ->icon('heroicon-o-user'),

        TextColumn::make('kostum_summary')
            ->label('Kostum')
            ->html()
            ->getStateUsing(fn ($record) =>
                '<ul style="padding-left:1rem;margin:0;">' .
                $record->orderItems
                    ->map(fn ($item) => '<li>' . optional($item->kostums)->nama_kostum . '</li>')
                    ->filter()
                    ->implode('') .
                '</ul>'
            ),

        TextColumn::make('tanggal_order')
            ->label('Order')
            ->date('d M Y')
            ->sortable(),

        TextColumn::make('tanggal_batas_sewa')
            ->label('End')
            ->date('d M Y')
            ->sortable(),

        TextColumn::make('durasi_sewa')
            ->label('Durasi')
            ->suffix(' hari')
            ->alignCenter(),

        TextColumn::make('total_harga')
            ->label('Total')
            ->money('IDR', true)
            ->alignEnd(),

        BadgeColumn::make('status')
            ->label('Status')
            ->colors([
                'primary' => 'Menunggu',
                'warning' => 'Diproses',
                'info'    => 'Siap diambil',
                'success' => 'Selesai',
                'danger'  => 'Dibatalkan',
            ])
            ->sortable(),
    ])

    ->filters([
        SelectFilter::make('status')
            ->label('Status Pesanan')
            ->options([
                'Menunggu'    => 'Menunggu',
                'Diproses'    => 'Diproses',
                'Siap diambil'=> 'Siap diambil',
                'Selesai'     => 'Selesai',
                'Dibatalkan'  => 'Dibatalkan',
            ]),
    ])

    ->actions([
        ViewAction::make(),
        EditAction::make(),
        DeleteAction::make()
            ->modalHeading('Hapus Pesanan')
            ->modalDescription('Yakin ingin menghapus pesanan ini?')
            ->modalButton('Ya, Hapus'),
    ])

    ->bulkActions([
        Tables\Actions\DeleteBulkAction::make()
            ->label('Hapus Terpilih')
            ->modalHeading('Hapus Pesanan Terpilih')
            ->modalDescription('Yakin ingin menghapus semua pesanan yang dipilih?')
            ->modalButton('Ya, Hapus Semua'),
    ]);

    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
