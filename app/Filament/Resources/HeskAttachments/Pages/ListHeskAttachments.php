<?php

namespace App\Filament\Resources\HeskAttachments\Pages;

use App\Filament\Resources\HeskAttachments\HeskAttachmentResource;
use Filament\Resources\Pages\ListRecords;

class ListHeskAttachments extends ListRecords
{
    protected static string $resource = HeskAttachmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
