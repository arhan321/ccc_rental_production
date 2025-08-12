<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kostum;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Admin\Resources\KostumResource\Pages;
use Filament\Tables\Columns\{ImageColumn, TextColumn};
use Filament\Forms\Components\{FileUpload, Select, Textarea, TextInput};

class KostumResource extends Resource
{
    protected static ?string $model = Kostum::class;
    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kostum';
    protected static ?string $navigationGroup = 'Product Management';

    public static function getPluralLabel(): string
{
    return 'Kostum';
}
    /* ------------------------------------------------------------------ */
    /*  FORM                                                              */
    /* ------------------------------------------------------------------ */
    public static function form(Form $form): Form
    {
        return $form->schema([
            /* ---------- DATA UTAMA ---------- */
            Forms\Components\Card::make([
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->required()
                    ->columnSpan(2),

                TextInput::make('nama_kostum')
                    ->label('Nama Kostum')
                    ->required()
                    ->columnSpan(2),

                Select::make('ukuran')
                    ->label('Ukuran')
                    ->options(['S'=>'S', 'M'=>'M', 'L'=>'L', 'XL'=>'XL'])
                    ->required(),

                TextInput::make('harga_sewa')
                    ->label('Harga Sewa')
                    ->numeric()
                    ->required(),

                /* stok kini optional karena kolom nullable */
                TextInput::make('stok')
                    ->label('Stok (opsional)')
                    ->numeric()
                    ->nullable(),          // <─ tidak required

                Select::make('status')
                    ->label('Status')
                    ->options(['Tersedia' => 'Tersedia', 'Terbooking' => 'Terbooking'])
                    ->default('Tersedia')
                    ->required(),
            ])->columns(2),

            /* ---------- GAMBAR & DESKRIPSI ---------- */
            Forms\Components\Card::make([
                FileUpload::make('image')
                    ->label('Gambar Kostum')
                    ->image()
                    ->required(),

                Textarea::make('deskripsi')->label('Deskripsi')->nullable(),
            ]),
        ]);
    }

    /* ------------------------------------------------------------------ */
    /*  TABLE                                                             */
    /* ------------------------------------------------------------------ */
    public static function table(Table $table): Table
    {
        return $table
    ->columns([
        TextColumn::make('id')
            ->label('ID')
            ->sortable()
            ->searchable()
            ->alignCenter(),

        TextColumn::make('nama_kostum')
            ->label('Nama')
            ->sortable()
            ->searchable()
            ->limit(30),

        TextColumn::make('category.name')
            ->label('Kategori')
            ->sortable()
            ->badge()
            ->color('gray'),

        TextColumn::make('ukuran')
            ->label('Ukuran')
            ->sortable()
            ->alignCenter(),

        TextColumn::make('harga_sewa')
            ->label('Harga')
            ->money('IDR', true)
            ->sortable()
            ->alignRight(),

        BadgeColumn::make('status')
            ->label('Status')
            ->colors([
                'success' => 'Tersedia',
                'danger' => 'Terbooking',
            ])
            ->sortable(),

        ImageColumn::make('image')
            ->label('Gambar')
            ->size(50)
            ->rounded(),
    ])

    ->filters([
        SelectFilter::make('status')
            ->label('Status')
            ->options([
                'Tersedia'   => 'Tersedia',
                'Terbooking' => 'Terbooking',
            ]),
    ])

    ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make()
            ->modalHeading('Hapus Kostum')
            ->modalDescription('Yakin ingin menghapus kostum ini?')
            ->modalButton('Ya, Hapus'),
    ])

    ->bulkActions([
        Tables\Actions\DeleteBulkAction::make()
            ->label('Hapus Terpilih')
            ->modalHeading('Konfirmasi Hapus')
            ->modalDescription('Yakin ingin menghapus semua kostum yang dipilih?')
            ->modalButton('Hapus Sekarang'),
    ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKostum::route('/'),
            'create' => Pages\CreateKostum::route('/create'),
            'edit'   => Pages\EditKostum::route('/{record}/edit'),
        ];
    }
}
