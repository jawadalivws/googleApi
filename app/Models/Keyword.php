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

    public function keyword_locations()
    {
        return $this->hasMany(KeywordLocation::class , 'keyword_id' , 'id');
    }

    public function keyword_location()
    {
        return $this->hasOne(KeywordLocation::class , 'keyword_id' , 'id');
    }

    // public function country()
    // {
    //     return $this->belongsToMany(Country::class , 'keyword_locations' , 'keyword_id' , 'country_id');
    // }

    // public function state()
    // {
    //     return $this->belongsToMany(State::class , 'keyword_locations' , 'keyword_id' , 'state_id');
    // }

    // public function city()
    // {
    //     return $this->belongsToMany(City::class , 'keyword_locations' , 'keyword_id' , 'city_id');
    // }
}
