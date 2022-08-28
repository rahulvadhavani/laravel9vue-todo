<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'status'
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) =>  Carbon::parse($value)->diffForHumans(null,false,true),
        );
    }
}
