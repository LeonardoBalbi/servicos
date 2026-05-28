<?php

namespace App\Filament\Resources\HeskCategories\Pages;

use App\Filament\Resources\HeskCategories\HeskCategoryResource;
use Filament\Resources\Pages\ListRecords;

class ListHeskCategories extends ListRecords
{
    protected static string $resource = HeskCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
