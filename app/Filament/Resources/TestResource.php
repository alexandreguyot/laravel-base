<?php
namespace App\Filament\Resources;
use App\Filament\Resources\TestResource\Pages;
use App\Models\Test;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = Test::class;
    protected static ?string $navigationIcon   = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup  = 'Formation';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\Select::make('course_id')->label('Cours')->relationship('course','title')->searchable()->preload(),
                Forms\Components\Select::make('lesson_id')->label('Leçon')->relationship('lesson','title')->searchable()->preload(),
                Forms\Components\TextInput::make('title')->label('Titre')->required()->maxLength(255),
                Forms\Components\Toggle::make('is_published')->label('Publié')->inline(false),
            ])->columns(2),
            Forms\Components\Textarea::make('description')->label('Description')->rows(3)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Titre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('course.title')->label('Cours')->badge(),
                Tables\Columns\TextColumn::make('lesson.title')->label('Leçon')->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\IconColumn::make('is_published')->label('Publié')->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('course')->label('Cours')->relationship('course','title'),
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
        return ['id' => 'ID', 'title' => 'Titre', 'is_published' => 'Publié', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return ['course.title' => 'Cours', 'lesson.title' => 'Leçon'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTests::route('/'),
            'create' => Pages\CreateTest::route('/create'),
            'edit'   => Pages\EditTest::route('/{record}/edit'),
        ];
    }
}
