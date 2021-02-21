<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable = ['title','image' ,'parent_id'];

  ///////////////////set image///////////////////////////////
  public function setImageAttribute($value){
    $img_name = time().rand(0,999).'.'.$value->getClientOriginalExtension();
    $value->move(base_path('/uploads/category'),$img_name);
    $this->attributes['image']= $img_name ;
  }

  public function getImageAttribute($value)
  {
    return url('/uploads/category/'.$value);
  }


  public function contents()
  {
    return $this->hasMany('App\Models\Content','category_id','id');
  }

  public function sub_cats()
  {
    return $this->hasMany('App\Models\Category','parent_id','id');
  }

  public function cat()
  {
    return $this->belongsTo('App\Models\Category','parent_id','id');
  }

}
