<?php

namespace GIS\Fileable;

use GIS\Fileable\Commands\ThumbnailClearCommand;
use GIS\Fileable\Helpers\DownloadActionsManager;
use GIS\Fileable\Helpers\ThumbnailActionsManager;
use GIS\Fileable\Livewire\ImageIndexWire;
use GIS\Fileable\Models\File;
use GIS\Fileable\Models\ThumbImage;
use GIS\Fileable\Observers\FileObserver;
use GIS\Fileable\Observers\ThumbImageObserver;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class FileableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Подключение views.
        $this->loadViewsFrom(__DIR__ . "/resources/views", "fa");

        // Livewire
        $component = config("fileable.customImageIndexComponent");
        Livewire::component(
            "fa-images",
            $component ?? ImageIndexWire::class
        );

        // Наблюдатели.
        $modelClass = config("fileable.customFileModel") ?? File::class;
        $observerClass = config("fileable.customFileObserver") ?? FileObserver::class;
        $modelClass::observe($observerClass);

        $modelClass = config("fileable.customThumbModel") ?? ThumbImage::class;
        $observerClass = config("fileable.customThumbObserver") ?? ThumbImageObserver::class;
        $modelClass::observe($observerClass);
    }

    public function register(): void
    {
        // Миграции.
        $this->loadMigrationsFrom(__DIR__ . "/database/migrations");

        // Подключение конфигурации.
        $this->mergeConfigFrom(
            __DIR__ . "/config/fileable.php", "fileable"
        );

        // Подключение переводов.
        $this->loadJsonTranslationsFrom(__DIR__ . "/lang");

        // Подключение routes
        $this->loadRoutesFrom(__DIR__ . "/routes/web.php");

        // Facades.
        $this->app->singleton("thumbnail-actions", function () {
            return new ThumbnailActionsManager;
        });
        $this->app->singleton("download-actions", function () {
            return new DownloadActionsManager;
        });

        // Commands.
        if ($this->app->runningInConsole()) {
            $this->commands([
                ThumbnailClearCommand::class
            ]);
        }
    }
}
