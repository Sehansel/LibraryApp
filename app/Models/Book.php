<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'bookTitle',
        'publisherId',
        'author',
        'stock',
        'bookDescription',
        'releaseDate'
    ];

    public function purchases(){
        return $this->hasMany(Purchase::class, 'bookId');
    }

    public function publisher(){
        return $this->belongsTo(Publisher::class, 'publisherId', 'id');
    }
}
