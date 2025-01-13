<?php

namespace GIS\Fileable\Models;

use GIS\Fileable\Interfaces\ThumbImageModelInterface;
use GIS\TraitsHelpers\Traits\ShouldHumanDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ThumbImage extends Model implements ThumbImageModelInterface
{
    use ShouldHumanDate;

    protected $fillable = [
        "path",
        "name",
        "mime",
        "image_id",
        "template",
    ];

    public function image(): BelongsTo
    {
        $modelClass = config("fileable.customFileModel") ?? File::class;
        return $this->belongsTo($modelClass, "image_id");
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
