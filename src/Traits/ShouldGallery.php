<?php

namespace GIS\Fileable\Traits;

use GIS\Fileable\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait ShouldGallery
{
    protected static function bootShouldGallery(): void
    {
        static::deleting(function (Model $model) {
            $model->clearImages();
        });
    }

    /**
     * @return string
     */
    public function getGalleryFileClassAttribute(): string
    {
        return config("fileable.customFileModel") ?? File::class;
    }

    /**
     * @return MorphMany
     */
    public function images(): MorphMany
    {
        // TODO: check if image
        return $this->morphMany($this->gallery_file_class, "fileable");
    }

    public function orderedImages(): MorphMany
    {
        return $this->images()->orderBy('priority');
    }

    /**
     * @return MorphOne
     */
    public function cover(): MorphOne
    {
        return $this->morphOne($this->gallery_file_class, "fileable")->oldest("priority");
    }

    public function livewireGalleryImage(TemporaryUploadedFile $file = null, string $fileName = null, string $path = null): void
    {
        if (! $file) return;
        $this->storeGalleryImage($file, $fileName, $path);
    }

    public function storeGalleryImageDirectly(UploadedFile $file, string $fileName = null, string $path = null): void
    {
        $this->storeGalleryImage($file, $fileName, $path);
    }

    public function clearImages(): void
    {
        foreach ($this->images as $image) {
            $image->delete();
        }
    }

    private function storeGalleryImage(UploadedFile $file, string $userFileName = null, string $path = null): void
    {
        if (! $path) $path =$this->getTable();
        $path = "/gallery/$path";
        // Получить расширение файла.
        $mime = $file->getClientOriginalExtension();
        // Сохранить файл.
        $fileName = Str::random(40) . "." . $mime;
        $path = $file->storeAs($path, $fileName);
        if (! empty($userFileName)) $name = $userFileName;
        else {
            $name = $file->getClientOriginalName();
            $name = str_replace(".{$mime}", "", $name);
        }
        // Тип файла.
        $type = "image";
        $priority = $this->getGalleryPriority();
        $image = $this->gallery_file_class::create(compact("path", "name", "mime", "type", "priority"));
        $this->images()->save($image);
    }

    private function getGalleryPriority()
    {
        $max = $this->gallery_file_class::query()
            ->where("fileable_type", $this::class)
            ->where("fileable_id", $this->id)
            ->where("type", "image")
            ->max("priority");
        if (! isset($max)) $max = -1;
        return $max + 1;
    }
}
