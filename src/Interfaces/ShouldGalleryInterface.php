<?php

namespace GIS\Fileable\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

interface ShouldGalleryInterface
{
    public function images(): MorphMany;
    public function cover(): MorphOne;
    public function livewireGalleryImage(TemporaryUploadedFile $file = null, string $fileName = null, string $path = null): void;
    public function clearImages(): void;
}
