<?php

namespace GIS\Fileable\Interfaces;

use ArrayAccess;
use JsonSerializable;
use Illuminate\Contracts\Broadcasting\HasBroadcastChannel;
use Illuminate\Contracts\Queue\QueueableEntity;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\CanBeEscapedWhenCastToString;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface FileModelInterface extends Arrayable, ArrayAccess, CanBeEscapedWhenCastToString, HasBroadcastChannel, Jsonable, JsonSerializable, QueueableEntity, UrlRoutable
{
    public function fileable(): MorphTo;
    public function thumbnails(): HasMany;
    public function getStorageAttribute(): string;
    public function getFileNameAttribute(): string;
}
