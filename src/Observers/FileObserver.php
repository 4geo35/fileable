<?php

namespace GIS\Fileable\Observers;

use GIS\Fileable\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileObserver
{
    public function deleted(Model $file): void
    {
        $this->clearThumbs($file);
        Storage::delete($file->path);
    }

    protected function clearThumbs(Model $file): void
    {
        /**
         * @var File $file
         */
        foreach ($file->thumbnails as $thumbnail) {
            $thumbnail->delete();
        }
    }
}
