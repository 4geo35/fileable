<?php

namespace GIS\Fileable\Models;

use GIS\Fileable\Interfaces\FileModelInterface;
use GIS\TraitsHelpers\Traits\ShouldHumanDate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class File extends Model implements FileModelInterface
{
    use HasFactory, ShouldHumanDate;

    protected $fillable = [
        "path",
        "name",
        "mime",
        "priority",
        "type",
        "parent_id",
        "template",
    ];

    /**
     * @return MorphTo
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasMany
     */
    public function thumbnails(): HasMany
    {
        $modelClass = config("fileable.customFileModel") ?? $this::class;
        return $this->hasMany($modelClass, "parent_id");
    }

    /**
     * @return string
     */
    public function getStorageAttribute(): string
    {
        return Storage::url($this->path);
    }

    /**
     * @return string
     */
    public function getSizeAttribute(): string
    {
        return Storage::size($this->path);
    }

    public function getHumanSizeAttribute(): string
    {
        $size = $this->size;
        if ($size > 0) {
            return size2word($size);
        } else {
            return $size;
        }
    }

    /**
     * @return string
     */
    public function getFileNameAttribute(): string
    {
        $exploded = explode("/", $this->path);
        return $exploded[count($exploded) - 1];
    }
}
