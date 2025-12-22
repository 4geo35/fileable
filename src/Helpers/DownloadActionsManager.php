<?php

namespace GIS\Fileable\Helpers;

use GIS\Fileable\Interfaces\FileModelInterface;
use GIS\Fileable\Models\File;
use Illuminate\Http\Response as IlluminateResponse;

class DownloadActionsManager
{
    public function findByName(string $fileName): FileModelInterface
    {
        try {
            $modelClass = config("fileable.customFileModel") ?? File::class;
            return $modelClass::query()
                ->where("path", "like", "%{$fileName}")
                ->firstOrFail();
        } catch (\Exception $ex) {
            abort(404);
        }
    }

    public function getMimeType($content): string
    {
        return finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);
    }

    public function buildResponse($content): IlluminateResponse
    {
        // define mime type
        $mime = $this->getMimeType($content);

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
}
