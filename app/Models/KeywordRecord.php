<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeywordRecord extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $guarded = [];

    public function keywords()
    {
        return $this->belongsTo(Keyword::class , 'keyword_id' , 'id');
    }
}
