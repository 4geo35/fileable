<?php

namespace GIS\Fileable\Traits;

use GIS\Fileable\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait ShouldImage
{
    protected static function bootShouldImage()
    {
        static::deleted(function (Model $model) {
            $model->clearImage(true);
        });
    }

    protected function getImageKey(): string
    {
        return ! empty($this->imageKey) ? $this->imageKey : "image_id";
    }

    /**
     * @return string
     */
    public function getImageFileClassAttribute(): string
    {
        return config("fileable.customFileModel") ?? File::class;
    }

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo($this->image_file_class, $this->getImageKey());
    }

    /**
     * @param string|null $path
     * @param string $inputName
     * @param string $field
     * @return void
     */
    public function uploadImage(string $path = null, string $inputName = "image", string $field = "title"): void
    {
        if (! request()->hasFile($inputName)) return;
        $file = request()->file($inputName);
        $this->storeImage($file, $path, $field);
    }

    /**
     * @param TemporaryUploadedFile|null $file
     * @param string|null $path
     * @param string $field
     * @return void
     */
    public function livewireImage(TemporaryUploadedFile $file = null, string $path = null, string $field = "title"): void
    {
        if (! $file) return;
        $this->storeImage($file, $path, $field);
    }

    /**
     * Удалить изображение.
     *
     * @param bool $deleted
     * @return void
     */
    public function clearImage(bool $deleted = false): void
    {
        $image = $this->image;
        if (! empty($image)) $image->delete();
        if (! $deleted) {
            $this->image()->disassociate();
            $this->save();
        }
    }

    /**
     * @param UploadedFile $file
     * @param string|null $path
     * @param string $field
     * @return void
     */
    private function storeImage(UploadedFile $file, string $path = null, string $field = "title"): void
    {
        if (! $path) $path = $this->getTable();
        // Удалить старое изображение.
        $this->clearImage();
        // Получить расширение файла.
        $mime = $file->getClientOriginalExtension();
        // Сохранить файл.
        $fileName = Str::random(40) . "." . $mime;
        $path = $file->storeAs($path, $fileName);
        // Получить имя файла.
        if (! empty($this->{$field})) $name = $this->{$field};
        else {
            $name = $file->getClientOriginalName();
            $name = str_replace(".{$mime}", "", $name);
        }
        // Тип файла изображение.
        $type = "image";
        // Создание файла.
        $image = $this->image_file_class::create(compact("path", "name", "mime", "type"));
        $this->image()->associate($image);
        $this->touch();
    }
}
