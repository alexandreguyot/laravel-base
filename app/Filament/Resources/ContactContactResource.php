<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ContactContactResource\Pages;
use App\Models\ContactContact;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactContactResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = ContactContact::class;
    protected static ?string $navigationIcon   = 'heroicon-o-user';
    protected static ?string $navigationGroup  = 'Contacts';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\Select::make('company_id')->label('Entreprise')->relationship('company','company_name')->searchable()->preload(),
                Forms\Components\TextInput::make('contact_first_name')->label('Prénom')->required(),
                Forms\Components\TextInput::make('contact_last_name')->label('Nom')->required(),
            ])->columns(3),
            Forms\Components\Section::make('Coordonnées')->schema([
                Forms\Components\TextInput::make('contact_email')->label('Email')->email(),
                Forms\Components\TextInput::make('contact_phone_1')->label('Téléphone 1'),
                Forms\Components\TextInput::make('contact_phone_2')->label('Téléphone 2'),
                Forms\Components\TextInput::make('contact_skype')->label('Skype'),
                Forms\Components\Textarea::make('contact_address')->label('Adresse')->rows(2)->columnSpan(2),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contact_first_name')->label('Prénom')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('contact_last_name')->label('Nom')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('company.company_name')->label('Entreprise')->badge(),
                Tables\Columns\TextColumn::make('contact_email')->label('Email')->searchable()->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\TextColumn::make('created_at')->label('Créé')->dateTime('d/m/Y')->sortable()->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('company')->label('Entreprise')->relationship('company','company_name'),
            ])
            ->headerActions([static::getExportAction()])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ])])
            ->defaultSort('id', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }


    public static function getExportColumns(): array
    {
        return ['id' => 'ID', 'contact_first_name' => 'Prénom', 'contact_last_name' => 'Nom', 'contact_email' => 'Email', 'contact_phone_1' => 'Téléphone', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return ['company.company_name' => 'Entreprise'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContactContacts::route('/'),
            'create' => Pages\CreateContactContact::route('/create'),
            'edit'   => Pages\EditContactContact::route('/{record}/edit'),
        ];
    }
}
