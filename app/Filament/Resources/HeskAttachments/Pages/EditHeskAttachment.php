<?php

namespace App\Filament\Resources\HeskAttachments\Pages;

use App\Filament\Resources\HeskAttachments\HeskAttachmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHeskAttachment extends EditRecord
{
    protected static string $resource = HeskAttachmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
