<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['file_path'];

    public function shops()
    {
        return $this->hasMany(Shop::class, 'image_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {
            if ($image->file_path) {
                Storage::disk('public')->delete($image->file_path);
            }
        });
    }
}
