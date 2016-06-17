<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'code', 'latitude', 'longitude', 'hint', 'status', 'circuit_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function circuit()
    {
        return $this->belongsTo('App\Circuit');
    }

    public function questions()
    {
        return $this->hasMany('App\NodeQuestion');
    }

    public function userDiscoverer()
    {
        return $this->belongsToMany('App\User')->withTimestamps()
          ->withPivot('circuit_id', 'status', 'date_status_0', 'date_status_1',
                      'date_status_2', 'node_question_id');
    }
}
