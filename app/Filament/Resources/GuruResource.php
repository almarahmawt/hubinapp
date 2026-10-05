<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuruResource\Pages;
use App\Models\Guru;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $modelLabel = 'Guru';

    protected static ?string $pluralModelLabel = 'Guru';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Bagian 1: Pengaturan Akun Login
            Section::make('Akun Akses Login')
                ->description(
                    'Hubungkan data guru ini dengan akun pengguna untuk login ke sistem.',
                )
                ->schema([
                    Select::make('user_id')
                        ->label('Pilih / Buat Akun User')
                        ->relationship('user', 'email')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        // Modal untuk membuatkan User baru secara langsung
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Nama User')
                                ->required(),
                            TextInput::make('username')
                                ->label('Username')
                                ->required()
                                ->unique(User::class, 'username'),
                            TextInput::make('email')
                                ->label('Email Login')
                                ->email()
                                ->required()
                                ->unique(User::class, 'email'),
                            TextInput::make('password')
                                ->label('Password')
                                ->password()
                                ->required()
                                ->dehydrateStateUsing(
                                    fn($state) => Hash::make($state),
                                ),
                        ])
                        ->createOptionUsing(function (array $data) {
                            $user = User::create($data);

                            // Otomatis assign Role 'Guru' sesuai syarat akses panel di User.php
                            if (method_exists($user, 'assignRole')) {
                                $user->assignRole('Guru');
                            }

                            return $user->id;
                        }),
                ]),

            // Bagian 2: Profil Detail Guru
            Section::make('Data Profil Guru')
                ->schema([
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

                    Toggle::make('is_aktif')
                        ->label('Status Aktif')
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Akun Login
                TextColumn::make('user.email')
                    ->label('Akun Login')
                    ->placeholder('Belum Punya Akun')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nip')->label('NIP')->searchable()->sortable(),

                // Menggunakan Accessor nama_lengkap dari Model Guru
                TextColumn::make('nama_lengkap')
                    ->label('Nama Guru')
                    ->searchable([
                        'nama',
                        'nip',
                        'gelar_depan',
                        'gelar_belakang',
                    ])
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
                    )
                    ->color(
                        fn(?string $state): string => match ($state) {
                            'L' => 'info',
                            'P' => 'warning',
                            default => 'gray',
                        },
                    ),

                TextColumn::make('no_hp')->label('No. HP'),

                IconColumn::make('is_aktif')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('jenis_kelamin')->options([
                    'L' => 'Laki-laki',
                    'P' => 'Perempuan',
                ]),

                TernaryFilter::make('is_aktif')->label('Status Aktif'),
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
