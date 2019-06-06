<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\AccessRequested;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Cas;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;
use Spatie\Permission\Models\Role;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
  * Where to redirect users after login.
  *
  * @var string
  */



  protected $redirectTo = '/getData';

  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }


  /**
  * Get the login username to be used by the controller.
  *
  * @return string
  */
  public function username()
  {
    return 'username';
  }

  public function loginCas()
  {

    Cas::authenticate();

    if($userID = Cas::getCurrentUser())
    {

      $isAccessRequest = Input::get('request',false);
      $attributes = Cas::getAttributes();

      $casUserInfo = [
        "firstname" => "",
        "lastname" => "",
        "email" => "",
        "prefix" => "",
        "suffix" => "",
        "middleinitial" => "",
        "address" => "",
        "phonenumber" => "",
      ];

      foreach($attributes as $key=>$value) {
        $casUserInfo[$key] = $value;
      }

      if($isAccessRequest) {

        $accountType = Input::get('account_type');

        $id = User::select('id')->where('username',$userID)->first();
        $id = $id['id'];

        if($id != "") {
          return redirect()->route('errors.duplicateRequest');
        }

        $casUserInfo['username'] = $userID;

        $this->copyMyBamaUser($casUserInfo);

        $id = User::select('id')->where('username',$userID)->first();
        $id = $id['id'];
        $user = User::findOrFail($id);

        $user->assignRole(Role::findOrFail($accountType));

        $admins = User::whereHas('roles', function($q){
            $q->where('name', 'Admin');
        })->get();

        Mail::to($admins)->send(new AccessRequested($user));

        return redirect()->route('errors.requestReceived');

      }

      $id = User::select('id')->where('username',$userID)->first();
      $id = $id['id'];
      if($id == "") {
        return redirect()->route('errors.perms.site');
      }

      $user = User::findOrFail($id);

      if($user->is_cas != 1) {
        return redirect()->route('errors.isLocal');
      }

      $this->updateMyBamaUser($id,$casUserInfo);

      $user = User::findOrFail($id);

      $this->guard()->loginUsingId($id);

      return redirect()->intended($this->redirectPath());

    }
  }

  public function updateMyBamaUser($id,$data) {

    $info = [
      'email' => $data['email'],
      'prefix' => $data['prefix'],
      'first_name' => $data['firstname'],
      'middle_initial' => $data['middleinitial'],
      'last_name' => $data['lastname'],
      'suffix' => $data['suffix'],
      'phone_number' => $data['phonenumber'],
      'address' => $data['address']
    ];

    foreach (array_keys($info) as $key) {
      if($info[$key] == "") {
        unset($info[$key]);
      }
    }

    $user = User::findOrFail($id);
    $user->fill($info)->save();

  }

  public function copyMyBamaUser($data) {

    return User::create([
      'username' => $data['username'],
      "password" => "isCas",
      'email' => $data['email'],
      'prefix' => $data['prefix'],
      'first_name' => $data['firstname'],
      'middleinitial' => $data['middleinitial'],
      'last_name' => $data['lastname'],
      'suffix' => $data['suffix'],
      'phone_number' => $data['phonenumber'],
      'is_cas' => 1
    ]);

  }

  public function logout(Request $request)
  {

    $isCas = Auth::user()->is_cas;

    $this->guard()->logout();

    $request->session()->invalidate();

    if($isCas == 1) {
      Cas::logout();
    }

    return redirect('/');
  }

}
