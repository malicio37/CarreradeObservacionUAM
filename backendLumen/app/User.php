<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname', 'email', 'type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function circuits()
    {
        return $this->belongsToMany('App\Circuit')->withTimestamps();
    }

    public function nodesDiscovered()
    {
        return $this->belongsToMany('App\Node')->withTimestamps()
          ->withPivot('circuit_id', 'status', 'date_status_0', 'date_status_1',
                      'date_status_2', 'node_question_id');
    }
}
