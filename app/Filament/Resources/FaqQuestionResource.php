<?php
namespace App\Filament\Resources;
use App\Filament\Resources\FaqQuestionResource\Pages;
use App\Models\FaqQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaqQuestionResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = FaqQuestion::class;
    protected static ?string $navigationIcon   = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup  = 'FAQ';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('category_id')->label('Catégorie')->relationship('category','category')->searchable()->preload(),
            Forms\Components\TextInput::make('question')->label('Question')->required()->maxLength(255)->columnSpanFull(),
            Forms\Components\RichEditor::make('answer')->label('Réponse')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')->label('Question')->searchable()->sortable()->limit(60),
                Tables\Columns\TextColumn::make('category.category')->label('Catégorie')->badge(),
                Tables\Columns\TextColumn::make('created_at')->label('Créé')->dateTime('d/m/Y')->sortable()->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('category')->label('Catégorie')->relationship('category','category'),
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
        return ['id' => 'ID', 'question' => 'Question', 'answer' => 'Réponse', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return ['category.category' => 'Catégorie'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFaqQuestions::route('/'),
            'create' => Pages\CreateFaqQuestion::route('/create'),
            'edit'   => Pages\EditFaqQuestion::route('/{record}/edit'),
        ];
    }
}
