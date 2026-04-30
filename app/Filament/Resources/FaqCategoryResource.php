<?php
namespace App\Filament\Resources;
use App\Filament\Resources\FaqCategoryResource\Pages;
use App\Models\FaqCategory;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaqCategoryResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = FaqCategory::class;
    protected static ?string $navigationIcon   = 'heroicon-o-folder';
    protected static ?string $navigationGroup  = 'FAQ';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('category')->label('Catégorie')->required()->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')->label('Catégorie')->searchable()->sortable(),
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
        return ['id' => 'ID', 'category' => 'Catégorie'];
    }

    public static function getExportRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFaqCategories::route('/'),
            'create' => Pages\CreateFaqCategory::route('/create'),
            'edit'   => Pages\EditFaqCategory::route('/{record}/edit'),
        ];
    }
}
