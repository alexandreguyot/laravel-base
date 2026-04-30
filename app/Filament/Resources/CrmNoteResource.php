<?php
namespace App\Filament\Resources;
use App\Filament\Resources\CrmNoteResource\Pages;
use App\Models\CrmNote;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Concerns\HasExportAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CrmNoteResource extends Resource
{
    use HasExportAction;

    protected static ?string $model            = CrmNote::class;
    protected static ?string $navigationIcon   = 'heroicon-o-chat-bubble-left';
    protected static ?string $navigationGroup  = 'CRM';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('customer_id')->label('Client')->relationship('customer', 'first_name')->searchable()->preload()->required(),
            Forms\Components\Textarea::make('note')->label('Note')->required()->rows(4)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.first_name')->label('Client')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('note')->label('Note')->limit(60),
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
        return ['id' => 'ID', 'note' => 'Note', 'created_at' => 'Créé le'];
    }

    public static function getExportRelations(): array
    {
        return ['customer.first_name' => 'Client'];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCrmNotes::route('/'),
            'create' => Pages\CreateCrmNote::route('/create'),
            'edit'   => Pages\EditCrmNote::route('/{record}/edit'),
        ];
    }
}
