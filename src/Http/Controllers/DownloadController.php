<?php

namespace GIS\Fileable\Http\Controllers;

use App\Http\Controllers\Controller;
use GIS\Fileable\Facades\DownloadActions;
use GIS\Fileable\Facades\ThumbnailActions;
use GIS\Fileable\Interfaces\FileModelInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    /**
     * @param string $template
     * @param string $fileName
     * @return IlluminateResponse
     */
    public function thumbnail(string $template, string $fileName): IlluminateResponse
    {
        $file = DownloadActions::findByName($fileName);
        $content = Storage::get($file->path);
        $mime = DownloadActions::getMimeType($content);
        if (! in_array($mime, config('fileable.imageTypes'))) { abort(404); }

        return match ($template) {
            "original" => DownloadActions::buildResponse($content),
            default => $this->makeImage($template, $file),
        };
    }

    public function download(string $fileName): IlluminateResponse|StreamedResponse
    {
        $file = DownloadActions::findByName($fileName);
        $content = Storage::get($file->path);
        $mime = DownloadActions::getMimeType($content);
        if ($mime == 'application/pdf' || in_array($mime, config('fileable.imageTypes'))) {
            return DownloadActions::buildResponse($content);
        }
        $title = $file->name;
        if (empty($title)) { $title = Str::random(); }
        $fileName = "$title.{$file->mime}";
        return Storage::download($file->path, $fileName);
    }

    protected function makeImage(string $template, FileModelInterface $image): IlluminateResponse
    {
        $content = ThumbnailActions::getFilteredContent($template, $image);
        return DownloadActions::buildResponse($content);
    }
}
