<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use HasExportAction;

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Utilisateurs';
    protected static ?string $label           = 'Utilisateur';
    protected static ?string $pluralLabel     = 'Utilisateurs';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informations')->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context) => $context === 'create'),
            ])->columns(2),

            Forms\Components\Section::make('Accès')->schema([
                Forms\Components\Select::make('roles')
                    ->label('Rôles')
                    ->relationship('roles', 'title')
                    ->multiple()
                    ->preload(),
                Forms\Components\Toggle::make('is_approved')
                    ->label('Approuvé')
                    ->default(true),
                Forms\Components\Select::make('locale')
                    ->label('Langue')
                    ->options(['fr' => 'Français', 'en' => 'English'])
                    ->default('fr'),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.title')
                    ->label('Rôles')
                    ->badge()
                    ->separator(','),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Approuvé')
                    ->boolean(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Vérifié')
                    ->boolean()
                    ->getStateUsing(fn ($record) => (bool) $record->email_verified_at),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\Filter::make('is_approved')
                    ->label('Approuvés seulement')
                    ->query(fn (Builder $query) => $query->where('is_approved', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }


    public static function getExportColumns(): array
    {
        return ['id' => 'ID', 'name' => 'Nom', 'email' => 'Email', 'is_approved' => 'Approuvé', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return ['roles.title' => 'Rôles'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
