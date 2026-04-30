<?php
namespace App\Filament\Resources;
use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = Task::class;
    protected static ?string $navigationIcon   = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup  = 'Tâches';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('name')->label('Tâche')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\Select::make('status_id')->label('Statut')->relationship('status','name')->searchable()->preload(),
                Forms\Components\Select::make('assigned_to_id')->label('Assignée à')->relationship('assignedTo','name')->searchable()->preload(),
                Forms\Components\DatePicker::make('due_date')->label('Échéance')->displayFormat('d/m/Y'),
            ])->columns(3),
            Forms\Components\Textarea::make('description')->label('Description')->rows(4)->columnSpanFull(),
            Forms\Components\Select::make('tags')->label('Tags')->relationship('tag','name')->multiple()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Tâche')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status.name')->label('Statut')->badge(),
                Tables\Columns\TextColumn::make('assignedTo.name')->label('Assignée à'),
                Tables\Columns\TextColumn::make('due_date')->label('Échéance')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Créé')->dateTime('d/m/Y')->sortable()->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')->label('Statut')->relationship('status','name'),
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
        return ['id' => 'ID', 'name' => 'Tâche', 'description' => 'Description', 'due_date' => 'Échéance', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return ['status.name' => 'Statut', 'assignedTo.name' => 'Assignée à', 'tag.name' => 'Tags'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit'   => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
