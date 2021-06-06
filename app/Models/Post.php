<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Post extends Pivot
{
    protected $table = 'posts';

    protected $primaryKey = 'id';

    protected $fillable = ['published_date','active','url','content_id','operator_id','user_id'];


    public function operator()
    {
        return $this->belongsTo('App\Models\Operator','operator_id','id');
    }

    public function content()
    {
        return $this->belongsTo('App\Models\Content','content_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Type','user_id','id');
    }
}
