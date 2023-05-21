<?php

namespace App\Filament\Resources\WebsiteLinkResource\Pages;

use App\Filament\Resources\WebsiteLinkResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteLink extends EditRecord
{
    protected static string $resource = WebsiteLinkResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
