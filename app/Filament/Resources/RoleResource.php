<?php
namespace App\Filament\Resources;
use App\Filament\Resources\RoleResource\Pages;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = Role::class;
    protected static ?string $navigationIcon   = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup  = 'Utilisateurs';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->label('Titre')->required()->maxLength(255),
            Forms\Components\Select::make('permissions')->label('Permissions')->relationship('permissions','title')->multiple()->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Titre')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('permissions.title')->label('Permissions')->badge()->separator(','),
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
        return ['id' => 'ID', 'title' => 'Titre', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return ['permissions.title' => 'Permissions'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
