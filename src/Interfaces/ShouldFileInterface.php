<?php

namespace GIS\Fileable\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

interface ShouldFileInterface
{
    public function getFileClassAttribute(): string;
    public function file(): BelongsTo;
    public function uploadFile(string $path = null, string $inputName = "file", string $field = "title"): void;
    public function livewireFile(TemporaryUploadedFile $file = null, string $path = null, string $field = "title"): void;
    public function clearFile(bool $deleted = false): void;
}
