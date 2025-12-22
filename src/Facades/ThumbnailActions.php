<?php

namespace GIS\Fileable\Facades;

use GIS\Fileable\Helpers\ThumbnailActionsManager;
use GIS\Fileable\Interfaces\FileModelInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed|string getFilteredContent(string $template, FileModelInterface $file)
 *
 * @see ThumbnailActionsManager
 */
class ThumbnailActions extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "thumbnail-actions";
    }
}
