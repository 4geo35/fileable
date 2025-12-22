<?php

namespace GIS\Fileable\Helpers;

use GIS\Fileable\Interfaces\FileModelInterface;
use GIS\Fileable\Interfaces\ThumbImageModelInterface;
use GIS\Fileable\Models\File;
use GIS\Fileable\Models\ThumbImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\EncodedImageInterface;

class ThumbnailActionsManager
{
    /**
     * @param string $template
     * @param FileModelInterface $file
     * @return string
     */
    public function getFilteredContent(string $template, FileModelInterface $file): string
    {
        $filtered = $this->getFilteredImage($template, $file->id);
        if (! empty($filtered)) {
            if (Storage::has($filtered->path)) {
                return Storage::get($filtered->path);
            } else {
                $filtered->delete();
            }
        }
        return $this->makeImage($template, $file);
    }

    /**
     * @param string $template
     * @param int $id
     * @return null|ThumbImageModelInterface
     */
    protected function getFilteredImage(string $template, int $id): ?ThumbImageModelInterface
    {
        $modelClass = config("fileable.customThumbModel") ?? ThumbImage::class;
        return $modelClass::query()
            ->where("image_id", $id)
            ->where("template", $template)
            ->first();
    }

    /**
     * @param string $template
     * @param FileModelInterface $file
     * @return EncodedImageInterface
     */
    protected function makeImage(string $template, FileModelInterface $file): EncodedImageInterface
    {
        $class = $this->getTemplate($template);
        $manager = new ImageManager(config("fileable.driver"));
        $intImage = $manager->read(Storage::get($file->path));
        $newImage = $intImage->modify($class);
        $content = $newImage->toWebp();

        $name = $file->name;
        $mime = "webp";
        $type = "image";
        $image_id = $file->id;
        $thumbFolder = config("fileable.thumbFolder");
        $path = "{$thumbFolder}/{$template}-{$file->id}-" . Str::random(40);
        Storage::put($path, $content);
        $modelClass = config("fileable.customThumbModel") ?? ThumbImage::class;
        $image = $modelClass::create(compact("path", "name", "mime", "type", "template", "image_id"));
        return $content;
    }

    protected function getTemplate(string $name)
    {
        $template = config("fileable.templates.{$name}");
        switch (true) {
            // closure template found
            case is_callable($template):
                return $template;

            // filter template found
            case class_exists($template):
                return new $template;

            default:
                // template not found
                abort(404);
        }
    }
}
