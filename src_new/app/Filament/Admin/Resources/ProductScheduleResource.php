<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kostum;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductSchedule;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use App\Filament\Admin\Resources\ProductScheduleResource\Pages;

class ProductScheduleResource extends Resource
{
    protected static ?string $model = ProductSchedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Product Schedule';
    protected static ?string $navigationGroup = 'Product Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                        ->label('Order')
                        ->relationship('order', 'nomor_pesanan')  // Fetch order's nomor_pesanan from Order model
                        ->required()
                        ->extraAttributes(['class' => 'rounded-lg border-2 border-gray-300 p-3 focus:ring-2 focus:ring-indigo-500 shadow-sm'])
                        ->columnSpan(2)
                        ->reactive(),

                Forms\Components\Card::make([
                    // Select Kostum and show the related kostum's status
                     Select::make('kostums_id')
                ->label('Select Kostum')
                ->relationship('kostums', 'nama_kostum')
                ->required()
                ->searchable()
                ->reactive()
                ->afterStateUpdated(fn ($state, $set) =>
                    $set('kostum_status', Kostum::find($state)?->status ?? '-')
                )
                ->columnSpan(2),

                    DatePicker::make('tanggal_mulai')
                        ->label('Start Date')
                        ->required()
                        ->extraAttributes(['class' => 'rounded-lg border-2 border-gray-300 p-3 focus:ring-2 focus:ring-indigo-500 shadow-sm'])
                        ->columnSpan(2),

                    DatePicker::make('tanggal_selesai')
                        ->label('End Date')
                        ->required()
                        ->extraAttributes(['class' => 'rounded-lg border-2 border-gray-300 p-3 focus:ring-2 focus:ring-indigo-500 shadow-sm'])
                        ->columnSpan(2),

                    // The status field will now be set dynamically based on the selected kostum
                    Placeholder::make('kostum_status')
                ->label('Status Kostum')
                ->content(fn ($get) =>
                    Kostum::find($get('kostums_id'))?->status ?? '-'
                )
                ->columnSpan(2),
                ])
                ->columns(2),  // Using 2 columns in form layout
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kostums.nama_kostum')  // Fetching the kostum name via relationship
                    ->label('Kostum')
                    ->sortable()
                    ->extraAttributes(['class' => 'text-sm font-semibold text-gray-600 hover:text-indigo-600 transition-all']),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Start Date')
                    ->sortable()
                    ->date()
                    ->extraAttributes(['class' => 'text-sm font-medium text-gray-600']),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('End Date')
                    ->sortable()
                    ->date()
                    ->extraAttributes(['class' => 'text-sm font-medium text-gray-600']),

                BadgeColumn::make('kostums.status')
                    ->label('Status')
                    ->colors([
                        'success' => 'Tersedia',
                        'warning' => 'Terbooking',
                    ])
                    ->sortable(),
                ])
                 ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->extraAttributes(['class' => 'bg-red-500 text-white hover:bg-red-600 transition-all rounded-lg py-2 px-4']),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define any relationships here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductSchedules::route('/'),
            'create' => Pages\CreateProductSchedule::route('/create'),
            'edit' => Pages\EditProductSchedule::route('/{record}/edit'),
        ];
    }
}
