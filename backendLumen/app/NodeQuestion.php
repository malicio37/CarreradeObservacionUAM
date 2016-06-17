<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NodeQuestion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question', 'answer', 'node_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function node()
    {
        return $this->belongsTo('App\Node');
    }
}
