<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles;


    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    public function findForPassport($identifier) {
        return User::orWhere(‘username’, $identifier)->where(‘active’, 1)->first();
    }

    protected $fillable = [
        'username', 'password', 'email', 'prefix', 'first_name',
        'middle_initial', 'last_name', 'suffix',
        'phone_number','job_position', 'address','is_cas','verified','active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'accountType'
    ];

    public function discussions()
  {
      return $this->hasMany('App\Discussion');
  }
}
