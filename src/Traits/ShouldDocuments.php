<?php

namespace GIS\Fileable\Traits;

use GIS\Fileable\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait ShouldDocuments
{
    protected static function bootShouldDocuments(): void
    {
        static::deleting(function (Model $model) {
            $model->clearDocuments();
        });
    }

    /**
     * @return string
     */
    public function getDocumentsFileClassAttribute(): string
    {
        return config("fileable.customFileModel") ?? File::class;
    }

    public function documents(): MorphMany
    {
        // TODO: check if file
        return $this->morphMany($this->documents_file_class, "fileable");
    }

    public function clearDocuments(): void
    {
        foreach ($this->documents as $document) {
            $document->delete();
        }
    }

    public function livewireDocumentsFile(TemporaryUploadedFile $file = null, string $fileName = null, string $path = null): void
    {
        if (! $file) return;
        $this->storeDocumentFile($file, $fileName, $path);
    }

    private function storeDocumentFile(UploadedFile $file, string $userFileName = null, string $path = null): void
    {
        if (! $path) $path =$this->getTable();
        $path = "/documents/$path";
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
        $type = "document";
        $priority = $this->getDocumentsPriority();
        $document = $this->documents_file_class::create(compact("path", "name", "mime", "type", "priority"));
        $this->documents()->save($document);
    }

    private function getDocumentsPriority()
    {
        $max = $this->documents_file_class::query()
            ->where("fileable_type", $this::class)
            ->where("fileable_id", $this->id)
            ->where("type", "document")
            ->max("priority");
        if (! isset($max)) $max = -1;
        return $max + 1;
    }
}
