<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = ['title', 'image'];

    ///////////////////set image///////////////////////////////
    public function setImageAttribute($value)
    {
        $img_name = time() . rand(0, 999) . '.' . $value->getClientOriginalExtension();
        $value->move(base_path('/uploads/provider'), $img_name);
        $this->attributes['image'] = $img_name;
    }

    public function getImageAttribute($value)
    {
        if ($value) {
            return url('/uploads/provider/' . $value);
        } else {
            return false;
        }

    }
    public function categories()
    {
        return $this->hasMany('App\Models\Category', 'provider_id', 'id');
    }
}
