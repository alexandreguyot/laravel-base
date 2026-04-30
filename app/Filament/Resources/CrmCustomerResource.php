<?php
namespace App\Filament\Resources;
use App\Filament\Resources\CrmCustomerResource\Pages;
use App\Models\CrmCustomer;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CrmCustomerResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = CrmCustomer::class;
    protected static ?string $navigationIcon   = 'heroicon-o-user-group';
    protected static ?string $navigationGroup  = 'CRM';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identité')->schema([
                Forms\Components\TextInput::make('first_name')->label('Prénom')->required(),
                Forms\Components\TextInput::make('last_name')->label('Nom')->required(),
                Forms\Components\Select::make('status_id')->label('Statut')->relationship('status','name')->searchable()->preload(),
            ])->columns(3),
            Forms\Components\Section::make('Contact')->schema([
                Forms\Components\TextInput::make('email')->label('Email')->email(),
                Forms\Components\TextInput::make('phone')->label('Téléphone'),
                Forms\Components\TextInput::make('skype')->label('Skype'),
                Forms\Components\TextInput::make('website')->label('Site web')->url(),
            ])->columns(2),
            Forms\Components\Textarea::make('address')->label('Adresse')->rows(2),
            Forms\Components\Textarea::make('description')->label('Notes')->rows(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->label('Prénom')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('last_name')->label('Nom')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status.name')->label('Statut')->badge(),
                Tables\Columns\TextColumn::make('email')->searchable()->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\TextColumn::make('phone')->label('Tél')->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\TextColumn::make('created_at')->label('Créé')->dateTime('d/m/Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')->label('Statut')->relationship('status','name'),
            ])
            ->headerActions([static::getExportAction()])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ])])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }


    public static function getExportColumns(): array
    {
        return ['id' => 'ID', 'first_name' => 'Prénom', 'last_name' => 'Nom', 'email' => 'Email', 'phone' => 'Téléphone', 'address' => 'Adresse', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return ['status.name' => 'Statut'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCrmCustomers::route('/'),
            'create' => Pages\CreateCrmCustomer::route('/create'),
            'edit'   => Pages\EditCrmCustomer::route('/{record}/edit'),
        ];
    }
}
