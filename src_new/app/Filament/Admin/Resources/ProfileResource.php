<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Profile;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\ProfileResource\Pages;
use App\Filament\Admin\Resources\ProfileResource\RelationManagers;

class ProfileResource extends Resource
{
    protected static ?string $model = Profile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Management User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('users_id')
                    ->relationship('user', 'name', function ($query) {
                        $query->whereHas('roles', function ($roleQuery) {
                            $roleQuery->where('name', 'User');
                        });
                    })
                    ->required(), 
                Forms\Components\TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->required(),
                Forms\Components\TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->required(),
                Forms\Components\TextInput::make('instagram')
                ->label('Instagram')
                ->placeholder('@username')
                ->regex('/^@?[A-Za-z0-9._]{1,30}$/')
                ->maxLength(255)
                ->helperText('Opsional — huruf, angka, titik, underscore (maks 30).'),
                Forms\Components\Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required(),
                Forms\Components\FileUpload::make('avatar_url')
                            ->label('Avatar')
                            ->image()
                            ->optimize('webp')
                            ->imageEditor()
                            ->imagePreviewHeight('250')
                            ->panelAspectRatio('7:2')
                            ->panelLayout('integrated')
                            ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
return $table
    ->columns([
        TextColumn::make('nama_lengkap')
            ->label('User')
            ->searchable()
            ->sortable()
            ->icon('heroicon-o-user')
            ->wrap()
            ->alignCenter()
            ->weight('bold')
            ->description(fn ($record) => 'Email: ' . ($record->user->email ?? '-'))
            ->badge()
            ->color('gray'),


        TextColumn::make('nomor_telepon')
            ->label('No. Telepon')
            ->searchable()
            ->icon('heroicon-o-phone')
            ->iconPosition('before'),

        TextColumn::make('instagram')
            ->label('Instagram')
            ->searchable()
            ->formatStateUsing(fn ($state) => $state ? '@' . $state : '-')
            ->icon('heroicon-o-camera'),

        TextColumn::make('jenis_kelamin')
            ->label('Jenis Kelamin')
            ->searchable()
            ->badge()
            ->color(fn ($state) => $state === 'Perempuan' ? 'pink' : 'blue')
            ->alignCenter(),

        TextColumn::make('tanggal_lahir')
            ->label('Tanggal Lahir')
            ->date('d M Y')
            ->sortable()
            ->alignCenter(),

        TextColumn::make('avatar_url')
            ->label('Foto Profil')
            ->url(fn ($state) => $state)
            ->openUrlInNewTab()
            ->limit(20)
            ->tooltip('Klik untuk melihat foto'),

        TextColumn::make('created_at')
            ->label('Dibuat')
            ->dateTime('d M Y H:i')
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true),

        TextColumn::make('updated_at')
            ->label('Diperbarui')
            ->dateTime('d M Y H:i')
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true),

        TextColumn::make('deleted_at')
            ->label('Dihapus')
            ->dateTime('d M Y H:i')
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true),
    ])

    ->filters([
        // Tambahkan filter jika diperlukan (misal: filter gender atau created_at range)
    ])

    ->actions([
        EditAction::make()
            ->icon('heroicon-o-pencil')
            ->color('warning')
            ->button()
            ->extraAttributes([
                'class' => 'text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded transition-all',
            ]),
    ])

    ->bulkActions([
        BulkActionGroup::make([
            DeleteBulkAction::make()
                ->label('Hapus Terpilih')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->extraAttributes([
                    'class' => 'text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded transition-all',
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
            'index' => Pages\ListProfiles::route('/'),
            'create' => Pages\CreateProfile::route('/create'),
            'edit' => Pages\EditProfile::route('/{record}/edit'),
        ];
    }
}
