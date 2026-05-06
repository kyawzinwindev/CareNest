<?php

namespace App\Filament\Resources\Specializations\Pages;

use App\Filament\Resources\Specializations\SpecializationResource;
use Filament\Resources\Pages\CreateRecord;
use Override;

class CreateSpecialization extends CreateRecord
{
    protected static string $resource = SpecializationResource::class;

    #[Override]
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
