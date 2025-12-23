<?php

namespace GIS\Fileable\Traits;

use GIS\Fileable\Interfaces\ShouldFileInterface;
use GIS\Fileable\Models\File;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait ShouldFile
{
    protected static function bootShouldFile(): void
    {
        static::deleted(function (ShouldFileInterface $model) {
            $model->clearFile(true);
        });

        static::updated(function (ShouldFileInterface $model) {
            if (
                $model->wasChanged("title") &&
                ! empty($model->title) &&
                ! empty($model->file)
            ) {
                $model->file->update([
                    "name" => $model->title,
                ]);
            }
        });
    }

    protected function getFileKey(): string
    {
        return ! empty($this->fileKey) ? $this->fileKey : "file_id";
    }

    public function getFileClassAttribute(): string
    {
        return config("fileable.customFileModel") ?? File::class;
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo($this->file_class, $this->getFileKey());
    }

    /**
     * @param string|null $path
     * @param string $inputName
     * @param string $field
     * @return void
     */
    public function uploadFile(string $path = null, string $inputName = "file", string $field = "title"): void
    {
        if (! request()->hasFile($inputName)) return;
        $file = request()->file($inputName);
        $this->storeFile($file, $path, $field);
    }

    /**
     * @param TemporaryUploadedFile|null $file
     * @param string|null $path
     * @param string $field
     * @return void
     */
    public function livewireFile(TemporaryUploadedFile $file = null, string $path = null, string $field = "title"): void
    {
        if (! $file) return;
        $this->storeFile($file, $path, $field);
    }

    /**
     * @param bool $deleted
     * @return void
     */
    public function clearFile(bool $deleted = false): void
    {
        $file = $this->file;
        if (! empty($file)) $file->delete();
        if (! $deleted) {
            $this->file()->disassociate();
            $this->save();
        }
    }

    /**
     * @param UploadedFile $file
     * @param string|null $path
     * @param string $field
     * @return void
     */
    private function storeFile(UploadedFile $file, string $path = null, string $field = "title"): void
    {
        if (! $path) $path = $this->getTable();
        // Удалить старое изображение.
        $this->clearFile();
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
        // Тип файла: любой файл.
        $type = "file";
        // Создание файла.
        $file = $this->file_class::create(compact("path", "name", "mime", "type"));
        $this->file()->associate($file);
        $this->save();
    }
}
