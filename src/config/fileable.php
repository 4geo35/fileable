<?php

return [
    "customFileModel" => null,
    "customFileObserver" => null,
    "customImageIndexComponent" => null,
    "customThumbController" => null,
    'lifetime' => 43200,
    "driver" => \Intervention\Image\Drivers\Imagick\Driver::class,
    "templates" => [
        "gallery-preview" => \GIS\Fileable\Templates\GalleyPreview::class,
        "small" => \GIS\Fileable\Templates\Small::class,
        "medium" => \GIS\Fileable\Templates\Medium::class,
        "large" => \GIS\Fileable\Templates\Large::class,
        "col-4-square" => \GIS\Fileable\Templates\Col4Square::class,
    ]
];
