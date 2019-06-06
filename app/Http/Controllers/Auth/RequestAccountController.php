<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Notifications\NewUser;
use App\Mail\AccessRequested;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use ReCaptcha\ReCaptcha;
class RequestAccountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Request Account Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/errors/pending';

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $roles = Role::all()->except(Role::findByName('Admin')->id);//Get all roles
        return view('auth.requestAccount')->with(['roles'=>$roles]);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $baseValidator =
            [
                'username' => 'unique:users,username|required|string|max:30',
                'account_type' => 'required|string|not_in:Select Account Type',
                'email' => 'required|string|email|max:50',
                'prefix' => 'nullable|string|max:5',
                'first_name' => 'required|string|max:30',
                'middle_initial' => 'nullable|string|max:1',
                'last_name' => 'required|string|max:30',
                'suffix' => 'nullable|string|max:5',
                'phone_number' => 'required|string',
            ];

        /*
        Check if it's a myBama account by seeing if the bamaId field
        had been filled. If so, replace the username with bamaId to
        be populated in the database. If not, check the username and
        password entered in the form.
        */
        if(isset($data['bamaId'])) {
            $data['username'] = $data['bamaId'];
        } else {
            $baseValidator['password'] = 'required|string|min:6|confirmed';
        }

        return Validator::make($data, $baseValidator);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        $data['is_cas'] = 1;

        if(isset($data['bamaId'])) {
            $data['username'] = $data['bamaId'];
            $data['password'] = "isCas";
            $data['is_cas'] = 1;
        }
        else {
          $data['password'] = bcrypt($data['password']);
          $data['is_cas'] = 0;
        }

        $user = User::create([
            'username' => $data['username'],
            'password' => $data['password'],
            'email' => $data['email'],
            'prefix' => $data['prefix'],
            'first_name' => $data['first_name'],
            'middle_initial' => $data['middle_initial'],
            'last_name' => $data['last_name'],
            'suffix' => $data['suffix'],
            'phone_number' => $data['phone_number'],
            'is_cas' => $data['is_cas']
        ]);


        $user->assignRole(Role::findOrFail($data['account_type']));

        $admins = User::whereHas('roles', function($q){
            $q->where('name', 'Admin');
        })->get();

        Mail::to($admins)->send(new AccessRequested($user));


        return $user;
    }
}
