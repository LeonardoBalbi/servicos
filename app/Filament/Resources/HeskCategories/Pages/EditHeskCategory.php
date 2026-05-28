<?php

namespace App\Filament\Resources\HeskCategories\Pages;

use App\Filament\Resources\HeskCategories\HeskCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHeskCategory extends EditRecord
{
    protected static string $resource = HeskCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
