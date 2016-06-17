<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Circuit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'status', 'description'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function nodes()
    {
        return $this->hasMany('App\Node');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
