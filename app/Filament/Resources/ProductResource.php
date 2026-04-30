<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = Product::class;
    protected static ?string $navigationIcon   = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup  = 'E-commerce';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('name')->label('Nom')->required()->maxLength(255),
                Forms\Components\TextInput::make('price')->label('Prix')->numeric()->prefix('€')->step('0.01'),
            ])->columns(2),
            Forms\Components\Textarea::make('description')->label('Description')->rows(4)->columnSpanFull(),
            Forms\Components\Select::make('categories')->label('Catégories')->relationship('category','name')->multiple()->preload(),
            Forms\Components\Select::make('tags')->label('Tags')->relationship('tag','name')->multiple()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nom')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('price')->label('Prix')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Catégories')->badge()->separator(','),
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
        return ['id' => 'ID', 'name' => 'Nom', 'price' => 'Prix', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return ['category.name' => 'Catégories'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
