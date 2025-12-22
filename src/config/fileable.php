<?php

return [
    // Models
    "customFileModel" => null,
    "customFileObserver" => null,

    "customThumbModel" => null,
    "customThumbObserver" => null,

    // Controllers
    "customDownloadController" => null,

    // Components
    "customImageIndexComponent" => null,

    // Settings
    "thumbFolder" => "thumb-image-filters",
    'lifetime' => 43200,
    "driver" => \Intervention\Image\Drivers\Imagick\Driver::class,
    "imageTypes" => ["image/jpeg", "image/png", "image/gif", "image/bmp", "image/webp", "image/svg+xml", "image/tiff", "image/ico"],

    // Templates
    "templates" => [
        "gallery-preview" => \GIS\Fileable\Templates\GalleyPreview::class,
        "small" => \GIS\Fileable\Templates\Small::class,
        "medium" => \GIS\Fileable\Templates\Medium::class,
        "large" => \GIS\Fileable\Templates\Large::class,
        "col-4-square" => \GIS\Fileable\Templates\Col4Square::class,
    ]
];
