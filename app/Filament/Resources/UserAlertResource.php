<?php
namespace App\Filament\Resources;
use App\Filament\Resources\UserAlertResource\Pages;
use App\Models\UserAlert;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserAlertResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = UserAlert::class;
    protected static ?string $navigationIcon   = 'heroicon-o-bell';
    protected static ?string $navigationGroup  = 'Utilisateurs';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('message')->label('Message')->required()->rows(3)->columnSpanFull(),
            Forms\Components\TextInput::make('link')->label('Lien')->url()->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('message')->label('Message')->limit(60)->searchable(),
                Tables\Columns\TextColumn::make('link')->label('Lien')->url()->toggleable(isToggledHiddenByDefault:true),
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
        return ['id' => 'ID', 'message' => 'Message', 'link' => 'Lien', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUserAlerts::route('/'),
            'create' => Pages\CreateUserAlert::route('/create'),
            'edit'   => Pages\EditUserAlert::route('/{record}/edit'),
        ];
    }
}
