<?php
namespace App\Filament\Resources;
use App\Filament\Resources\LessonResource\Pages;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = Lesson::class;
    protected static ?string $navigationIcon   = 'heroicon-o-book-open';
    protected static ?string $navigationGroup  = 'Formation';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\Select::make('course_id')->label('Cours')->relationship('course','title')->searchable()->preload()->required(),
                Forms\Components\TextInput::make('title')->label('Titre')->required()->maxLength(255),
                Forms\Components\TextInput::make('position')->label('Position')->numeric()->default(0),
                Forms\Components\Toggle::make('is_published')->label('Publié')->inline(false),
                Forms\Components\Toggle::make('is_free')->label('Gratuit')->inline(false),
            ])->columns(3),
            Forms\Components\Textarea::make('short_text')->label('Résumé')->rows(2)->columnSpanFull(),
            Forms\Components\RichEditor::make('long_text')->label('Contenu')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Titre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('course.title')->label('Cours')->badge(),
                Tables\Columns\TextColumn::make('position')->label('Pos.')->sortable(),
                Tables\Columns\IconColumn::make('is_published')->label('Publié')->boolean(),
                Tables\Columns\IconColumn::make('is_free')->label('Gratuit')->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('course')->label('Cours')->relationship('course','title'),
                Tables\Filters\TernaryFilter::make('is_published')->label('Publié'),
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
        return ['id' => 'ID', 'title' => 'Titre', 'position' => 'Position', 'is_published' => 'Publié', 'is_free' => 'Gratuit'];
    }

    public static function getExportRelations(): array
    {
        return ['course.title' => 'Cours'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit'   => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
