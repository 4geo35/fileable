<?php

namespace GIS\Fileable\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

interface ShouldImageInterface
{
    public function getImageFileClassAttribute(): string;
    public function image(): BelongsTo;
    public function uploadImage(string $path = null, string $inputName = "image", string $field = "title"): void;
    public function livewireImage(TemporaryUploadedFile $file = null, string $path = null, string $field = "title"): void;
    public function clearImage(bool $deleted = false): void;
}
