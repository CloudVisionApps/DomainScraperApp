<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebsiteLinkResource\Pages;
use App\Filament\Resources\WebsiteLinkResource\RelationManagers;
use App\Models\WebsiteLink;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WebsiteLinkResource extends Resource
{
    protected static ?string $model = WebsiteLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('website_link')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebsiteLinks::route('/'),
            'create' => Pages\CreateWebsiteLink::route('/create'),
            'edit' => Pages\EditWebsiteLink::route('/{record}/edit'),
        ];
    }
}
