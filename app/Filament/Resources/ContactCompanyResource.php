<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ContactCompanyResource\Pages;
use App\Models\ContactCompany;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactCompanyResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = ContactCompany::class;
    protected static ?string $navigationIcon   = 'heroicon-o-building-office';
    protected static ?string $navigationGroup  = 'Contacts';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('company_name')->label('Nom')->required()->maxLength(255),
                Forms\Components\TextInput::make('company_email')->label('Email')->email(),
                Forms\Components\TextInput::make('company_website')->label('Site web')->url(),
                Forms\Components\Textarea::make('company_address')->label('Adresse')->rows(2),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')->label('Entreprise')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('company_email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('company_website')->label('Site web')->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\TextColumn::make('created_at')->label('Créé')->dateTime('d/m/Y')->sortable()->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

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
        return ['id' => 'ID', 'company_name' => 'Entreprise', 'company_email' => 'Email', 'company_website' => 'Site', 'company_address' => 'Adresse', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContactCompanies::route('/'),
            'create' => Pages\CreateContactCompany::route('/create'),
            'edit'   => Pages\EditContactCompany::route('/{record}/edit'),
        ];
    }
}
