<?php

namespace GIS\Fileable\Observers;

use GIS\Fileable\Interfaces\ThumbImageModelInterface;
use Illuminate\Support\Facades\Storage;

class ThumbImageObserver
{
    public function deleted(ThumbImageModelInterface $model): void
    {
        if (Storage::has($model->path)) Storage::delete($model->path);
    }
}
