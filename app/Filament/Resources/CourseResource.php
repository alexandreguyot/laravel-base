<?php
namespace App\Filament\Resources;
use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = Course::class;
    protected static ?string $navigationIcon   = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup  = 'Formation';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('title')->label('Titre')->required()->maxLength(255),
                Forms\Components\Select::make('teacher_id')->label('Formateur')->relationship('teacher','name')->searchable()->preload(),
                Forms\Components\TextInput::make('price')->label('Prix')->numeric()->prefix('€'),
                Forms\Components\Toggle::make('is_published')->label('Publié')->inline(false),
            ])->columns(2),
            Forms\Components\Textarea::make('description')->label('Description')->rows(4)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Titre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')->label('Formateur'),
                Tables\Columns\TextColumn::make('price')->label('Prix')->money('EUR')->sortable(),
                Tables\Columns\IconColumn::make('is_published')->label('Publié')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Créé')->dateTime('d/m/Y')->sortable()->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('is_published')->label('Publié'),
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
        return ['id' => 'ID', 'title' => 'Titre', 'price' => 'Prix', 'is_published' => 'Publié', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return ['teacher.name' => 'Formateur'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit'   => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
