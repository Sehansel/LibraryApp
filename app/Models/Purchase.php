<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'bookId'
    ];

    public function book(){
        return $this->belongsTo(Book::class, 'bookId', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
