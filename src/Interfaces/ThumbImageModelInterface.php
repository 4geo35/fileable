<?php

namespace GIS\Fileable\Interfaces;

use ArrayAccess;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JsonSerializable;
use Illuminate\Contracts\Broadcasting\HasBroadcastChannel;
use Illuminate\Contracts\Queue\QueueableEntity;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\CanBeEscapedWhenCastToString;
use Illuminate\Contracts\Support\Jsonable;

interface ThumbImageModelInterface extends Arrayable, ArrayAccess, CanBeEscapedWhenCastToString, HasBroadcastChannel, Jsonable, JsonSerializable, QueueableEntity, UrlRoutable
{
    public function image(): BelongsTo;
    public function getStorageAttribute(): string;
    public function getSizeAttribute(): string;
    public function getHumanSizeAttribute(): string;
    public function getFileNameAttribute(): string;
}
