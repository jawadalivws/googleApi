<?php

namespace App\Models;
use App\Models\KeywordRecord;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $guarded = [];
    
    public function keyword_records()
    {
        return $this->hasMany(KeywordRecord::class , 'keyword_id');
    }
}
