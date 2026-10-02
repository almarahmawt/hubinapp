<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Filament\Resources\GuruResource\RelationManagers;
use App\Models\Guru;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $modelLabel = 'Guru';

    protected static ?string $pluralModelLabel = 'Guru';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nip')
                ->label('NIP')
                ->unique(ignoreRecord: true)
                ->maxLength(30),

            TextInput::make('gelar_depan')
                ->label('Gelar Depan')
                ->placeholder('Contoh: Dr. / Drs. / H.')
                ->maxLength(50),

            TextInput::make('nama')
                ->label('Nama Lengkap (Tanpa Gelar)')
                ->required()
                ->maxLength(255),

            TextInput::make('gelar_belakang')
                ->label('Gelar Belakang')
                ->placeholder('Contoh: S.Pd., M.Kom.')
                ->maxLength(100),

            Select::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                ->options([
                    'L' => 'Laki-laki',
                    'P' => 'Perempuan',
                ]),

            TextInput::make('no_hp')
                ->label('Nomor WhatsApp / HP')
                ->tel()
                ->maxLength(20),

            Textarea::make('alamat')
                ->label('Alamat Domisili')
                ->columnSpanFull(),

            Toggle::make('is_aktif')->label('Status Aktif')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nip')->label('NIP')->searchable()->sortable(),

            // Menggunakan Accessor nama_lengkap dari Model
            TextColumn::make('nama_lengkap')
                ->label('Nama Guru')
                ->searchable(['nama', 'nip', 'gelar_depan', 'gelar_belakang'])
                ->sortable(),

            TextColumn::make('jenis_kelamin')
                ->label('L/P')
                ->badge()
                ->formatStateUsing(
                    fn(?string $state): string => match ($state) {
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                        default => '-',
                    },
                ),

            TextColumn::make('no_hp')->label('No. HP'),

            IconColumn::make('is_aktif')->label('Aktif')->boolean(),
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
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }
}
