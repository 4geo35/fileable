<?php

namespace GIS\Fileable\Http\Controllers;

use App\Http\Controllers\Controller;
use GIS\Fileable\Facades\ThumbnailActions;
use GIS\Fileable\Interfaces\FileModelInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response as IlluminateResponse;

class ThumbnailController extends Controller
{
    /**
     * @param string $template
     * @param string $fileName
     * @return IlluminateResponse
     */
    public function show(string $template, string $fileName): IlluminateResponse
    {
        $file = ThumbnailActions::findByName($fileName);
        if ($file->type != "image") abort(404);

        return match ($template) {
            "original" => $this->buildResponse(Storage::get($file->path)),
            default => $this->makeImage($template, $file),
        };
    }

    protected function buildResponse($content): IlluminateResponse
    {
        // define mime type
        $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);

        // respond with 304 not modified if browser has the image cached
        $etag = md5($content);
        $not_modified = isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag;
        $content = $not_modified ? NULL : $content;
        $status_code = $not_modified ? 304 : 200;

        // return http response
        return new IlluminateResponse($content, $status_code, array(
            'Content-Type' => $mime,
            'Cache-Control' => 'max-age='.(config('fileable.lifetime')*60).', public',
            'Content-Length' => strlen($content),
            'Etag' => $etag
        ));
    }

    protected function makeImage(string $template, FileModelInterface $image): IlluminateResponse
    {
        $content = ThumbnailActions::getFilteredContent($template, $image);
        return $this->buildResponse($content);
    }
}
