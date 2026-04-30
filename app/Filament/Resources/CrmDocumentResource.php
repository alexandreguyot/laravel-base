<?php
namespace App\Filament\Resources;
use App\Filament\Resources\CrmDocumentResource\Pages;
use App\Models\CrmDocument;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CrmDocumentResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = CrmDocument::class;
    protected static ?string $navigationIcon   = 'heroicon-o-paper-clip';
    protected static ?string $navigationGroup  = 'CRM';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('customer_id')->label('Client')->relationship('customer','first_name')->searchable()->preload()->required(),
            Forms\Components\TextInput::make('name')->label('Nom du document')->required()->maxLength(255),
            Forms\Components\FileUpload::make('document')->label('Fichier')->disk('public')->directory('crm-documents'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.first_name')->label('Client')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Nom')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Créé')->dateTime('d/m/Y')->sortable(),
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }


    public static function getExportColumns(): array
    {
        return ['id' => 'ID', 'name' => 'Nom document', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return ['customer.first_name' => 'Client'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCrmDocuments::route('/'),
            'create' => Pages\CreateCrmDocument::route('/create'),
            'edit'   => Pages\EditCrmDocument::route('/{record}/edit'),
        ];
    }
}
