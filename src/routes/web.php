<?php

use Illuminate\Support\Facades\Route;
use GIS\Fileable\Http\Controllers\DownloadController;

Route::middleware(["web"])
    ->group(function () {
        $controller = config("fileable.customDownloadController") ?? DownloadController::class;
        Route::get("/thumbnail/{template}/{filename}", [$controller, "thumbnail"])
            ->name("thumb-img");
        Route::get("/download/{filename}", [$controller, "download"])
            ->name("download-file");
    });
