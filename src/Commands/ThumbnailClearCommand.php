<?php

namespace GIS\Fileable\Commands;

use GIS\Fileable\Models\File;
use GIS\Fileable\Models\ThumbImage;
use Illuminate\Console\Command;
class ThumbnailClearCommand extends Command
{
    protected $signature = "thumb:clear
                    { --template= : clear only by template }
                    { --all : clear all }";

    protected $description = "Clear generated thumbs";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($this->option("all")) $thumbs = $this->getAll();
        elseif ($template = $this->option("template")) $thumbs = $this->getByTemplate($template);
        else $thumbs = [];

        foreach ($thumbs as $thumb) {
            $thumb->delete();
        }
        return 0;
    }

    protected function getByTemplate(string $template)
    {
        return $this->getFileModel()::query()
            ->select("id", "path")
            ->where("template", $template)
            ->get();
    }

    protected function getAll()
    {
        return $this->getFileModel()::query()
            ->select("id", "path")
            ->get();
    }

    private function getFileModel(): mixed
    {
        return config("fileable.customThumbModel") ?? ThumbImage::class;
    }
}
