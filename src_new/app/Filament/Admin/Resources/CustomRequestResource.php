<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CustomRequest;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\CustomRequestResource\Pages;
use App\Filament\Admin\Resources\CustomRequestResource\RelationManagers;

class CustomRequestResource extends Resource
{
    protected static ?string $model = CustomRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Request Kostum';
    protected static ?string $navigationGroup = 'Request Management';

    public static function form(Form $form): Form
    {
        return $form
    ->schema([
        Forms\Components\Card::make()
            ->columns(2)
            ->schema([
                Select::make('profile_id')
                    ->label('Nama Pemesan')
                    ->relationship('profile', 'nama_lengkap')
                    ->required()
                    ->columnSpan(2),

                TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(1),

                TextInput::make('telepon')
                    ->label('Nomor Telepon / WhatsApp')
                    ->required()
                    ->maxLength(20)
                    ->columnSpan(1),

                FileUpload::make('referensi')
                    ->label('Upload Referensi Kostum')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->maxSize(2048)
                    ->required()
                    ->helperText('Format JPG/PNG. Max 2MB.')
                    ->columnSpan(2),

                Select::make('ukuran')
                    ->options([
                        'S' => 'S',
                        'M' => 'M',
                        'L' => 'L',
                        'XL' => 'XL',
                        'Custom' => 'Custom',
                    ])
                    ->required()
                    ->live()
                    ->columnSpan(1),

                FileUpload::make('template')
                    ->label('Upload Template Ukuran (Jika Custom)')
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/jpeg', 'image/png'])
                    ->maxSize(2048)
                    ->helperText('Format PDF, DOC, JPG, PNG. Max 2MB.')
                    ->visible(fn (Forms\Get $get) => $get('ukuran') === 'Custom')
                    ->columnSpan(1),

                Textarea::make('catatan')
                    ->label('Catatan Tambahan')
                    ->placeholder('Misalnya warna khusus, tema acara, dsb.')
                    ->columnSpan(2),

                Select::make('status')
                    ->options([
                        'Menunggu' => 'Menunggu',
                        'Diproses' => 'Diproses',
                        'Selesai' => 'Selesai',
                        'Ditolak' => 'Ditolak',
                    ])
                    ->required()
                    ->default('Menunggu')
                    ->columnSpan(1),
            ])
            ->columnSpan('full'),
    ]);

    }

    public static function table(Table $table): Table
    {
        return $table
    ->columns([
        TextColumn::make('nama')
            ->label('Nama Pengajuan')
            ->searchable()
            ->sortable()
            ->wrap()
            ->icon('heroicon-o-user')
            ->description(fn ($record) => $record->profile?->nama_lengkap),

        TextColumn::make('telepon')
            ->label('No. Telepon')
            ->icon('heroicon-o-phone')
            ->copyable()
            ->copyMessage('Nomor disalin!')
            ->searchable(),

        TextColumn::make('ukuran')
            ->label('Ukuran')
            ->badge()
            ->color(fn ($state) => match ($state) {
                'S' => 'gray',
                'M' => 'info',
                'L' => 'primary',
                'XL' => 'secondary',
                'Custom' => 'warning',
            }),

        BadgeColumn::make('status')
            ->label('Status')
            ->colors([
                'gray' => 'Menunggu',
                'warning' => 'Diproses',
                'success' => 'Selesai',
                'danger' => 'Ditolak',
            ])
            ->formatStateUsing(fn ($state) => strtoupper($state)),

        TextColumn::make('created_at')
            ->label('Diajukan')
            ->date('d M Y H:i')
            ->icon('heroicon-o-clock')
            ->sortable(),
    ])
    ->actions([
            ViewAction::make(),
            DeleteAction::make(),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ])->label('Select Actions'),
        ])
        ->filters([
            SelectFilter::make('status')
                ->label('Filter Status')
                ->options([
                    'menunggu' => 'Menunggu',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                    'ditolak' => 'Ditolak',
                ])
                ->placeholder('Semua Status'),
        ])
        ->defaultSort('created_at', 'desc');

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
            'index' => Pages\ListCustomRequests::route('/'),
            'create' => Pages\CreateCustomRequest::route('/create'),
            'edit' => Pages\EditCustomRequest::route('/{record}/edit'),
        ];
    }
}
