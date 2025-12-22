<?php

namespace GIS\Fileable\Facades;

use GIS\Fileable\Helpers\DownloadActionsManager;
use GIS\Fileable\Interfaces\FileModelInterface;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Facade;

/**
 * @method static FileModelInterface findByName(string $fileName)
 * @method static string getMimeType($content)
 * @method static IlluminateResponse buildResponse($content)
 *
 * @see DownloadActionsManager
 */
class DownloadActions extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "download-actions";
    }
}
