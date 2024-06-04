<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeywordLocation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function city()
    {
        return $this->hasOne(City::class, 'id' , 'city_id');
    }

    public function state()
    {
        return $this->hasOne(State::class, 'id' , 'state_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id' , 'country_id');
    }

    public function kerword_records()
    {
        return $this->hasManye(KeywordRecord::class, 'keyword_location' , 'id');
    }
}
