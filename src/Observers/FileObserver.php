<?php

namespace GIS\Fileable\Observers;

use GIS\Fileable\Interfaces\FileModelInterface;
use GIS\Fileable\Models\File;
use Illuminate\Support\Facades\Storage;

class FileObserver
{
    public function deleted(FileModelInterface $file): void
    {
        $this->clearThumbs($file);
        if (Storage::has($file->path)) Storage::delete($file->path);
    }

    protected function clearThumbs(FileModelInterface $file): void
    {
        /**
         * @var File $file
         */
        foreach ($file->thumbnails as $thumbnail) {
            $thumbnail->delete();
        }
    }
}
