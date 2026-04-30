<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ContentPageResource\Pages;
use App\Models\ContentPage;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContentPageResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = ContentPage::class;
    protected static ?string $navigationIcon   = 'heroicon-o-document-text';
    protected static ?string $navigationGroup  = 'Contenu';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('title')->label('Titre')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->label('Slug')->maxLength(255),
                Forms\Components\Toggle::make('use_builder')->label('Page Builder actif')->inline(false),
            ])->columns(3),
            Forms\Components\Textarea::make('excerpt')->label('Extrait')->rows(2)->columnSpanFull(),
            Forms\Components\RichEditor::make('page_text')->label('Contenu')->columnSpanFull(),
            Forms\Components\Select::make('category')->label('Catégories')->relationship('category','name')->multiple()->preload(),
            Forms\Components\Select::make('tag')->label('Tags')->relationship('tag','name')->multiple()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Titre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('Slug')->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\IconColumn::make('use_builder')->label('Builder')->boolean(),
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
        return ['id' => 'ID', 'title' => 'Titre', 'slug' => 'Slug', 'use_builder' => 'Builder', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContentPages::route('/'),
            'create' => Pages\CreateContentPage::route('/create'),
            'edit'   => Pages\EditContentPage::route('/{record}/edit'),
        ];
    }
}
