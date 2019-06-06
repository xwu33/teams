<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Form;
use App\User;
use App\Table;
use App\Nav;
use App\Search;
use App\Setting;
use App\Signup;
use App\Mail\AccountVerified;
use Auth;
use Excel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Enables us to output flash messaging
use Session;

class UserController extends Controller {

    public function __construct() {
        $this->middleware(['auth','verified']);
        $this->middleware(['isAdmin']);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request) {
        //Get all users and pass it to the view
        if(Auth::user()->hasRole('Admin')) {

            $userTable = new Table;

            $cols = [
              [
                'displayName' => 'First Name',
                'varName' => 'first_name',
                'searchable' => true
              ],
              [
                'displayName' => 'Last Name',
                'varName' => 'last_name',
                'searchable' => true
              ],
              [
                'displayName' => 'Roles',
                'varName' => 'roles',
                'searchable' => true,
                'whereField' => DB::raw(
                              "(
                                SELECT group_concat(roles.name SEPARATOR ', ')
                                FROM users as u2
                                JOIN model_has_roles ON model_has_roles.model_id = u2.id
                                JOIN roles ON model_has_roles.role_id = roles.id WHERE u2.id=users.id
                                )"
                              )
              ],
              [
                'displayName' => 'Signups',
                'varName' => 'signups'
              ],
              [
                'displayName' => 'Edit',
                'varName' => 'edit'
              ],
              [
                'displayName' => 'Delete',
                'varName' => 'delete'
              ],
            ];

            $userTable->addColumns($cols);

            $query = DB::table('users');

            $query = $query
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                //->select('users.*', DB::raw("group_concat(name SEPARATOR ', ') as roles"))
                ->groupBy('users.id')
                ->select(
                  'users.first_name', 'users.last_name', 'users.id',
                  DB::raw(
                    "(
                      SELECT group_concat(roles.name SEPARATOR ', ')
                      FROM users as u2
                      JOIN model_has_roles ON model_has_roles.model_id = u2.id
                      JOIN roles ON model_has_roles.role_id = roles.id WHERE u2.id=users.id
                      ) AS roles"
                    )
                  );

            $query = Search::searchQuery($request, $userTable->cols, $query);

            $query = $query->where('verified', 1)->whereNull('deleted_at');

            if($request->column) {
              if($request->dir){
                $query = $query->orderBy($request->column, $request->dir);
              } else {
                $query = $query->orderBy($request->column);
              }
            }

            $query = $query->paginate(Setting::byName('Pagination'));

            foreach($query as $row) {

              $showSignups = Signup::where('user_id', '=', $row->id)
                ->join('exams', 'exam_id', '=', 'exams.id')
                ->whereBetween('date', [date('Y-m-d'), $this->endOfSemester()])->count();
              if($showSignups > 0) {
                $row->signups = [
                  "method" => "get",
                  "route" => ["signups.view",$row->id],
                  "name" => ($showSignups > 1) ? $showSignups." Signups" : $showSignups." Signup",
                  "class" => "default"
                ];
              }

              $row->edit = [
                "method" => "get",
                "route" => ["users.edit",$row->id],
                "name" => "Edit",
                "icon" => "pencil",
                "class" => "warning"
              ];

              $row->delete = [
                "method" => "DELETE",
                "route" => ["users.destroy",$row->id],
                "name" => "Delete",
                "icon" => "trash",
                "class" => "danger"
              ];

            }

            $userTable->addRows($query);



            $unverified = DB::table('users')
                ->where('verified', 0)
                ->whereNull('deleted_at')->count();

            return view('users.index')
                ->with(
                    [
                        'unverifiedCount' => $unverified,
                        'tableData' => $userTable
                    ]
                );
        } else {
            return view('users.index')->with('users', [Auth::user()]);
        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create() {
    //Get all roles and pass it to the view
        $roles = Role::get();
        /*
        $formMaker= new Form;
        $formMaker->addText('prefix', 'Prefix');
        $formMaker->addText('first_name', 'First Name');
        $formMaker->addText('middle_initial', 'Middle Initial');
        $formMaker->addText('last_name', 'Last Name');
        $formMaker->addText('suffix', 'Suffix');
        $formMaker->addText('username', 'Username');
        $formMaker->addEmail('email', 'Email');
        $formMaker->addText('phone_number', 'Phone Number');
        $formMaker->addPassword('password', 'Password');
        $formMaker->addPassword('password_confirmation', 'Confirm Password');
        return view('users.create', ['roles'=>$roles])->with(['formData'=> $formMaker->render()]);*/
        return view('users.create', ['roles'=>$roles]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request) {
    //Validate name, email and password fields

        $baseValidator = [
            'username' => 'unique:users,username|required|string|max:30',
            'email' => 'required|string|email|max:50',
            'prefix' => 'nullable|string|max:5',
            'first_name' => 'required|string|max:30',
            'middle_initial' => 'nullable|string|max:1',
            'last_name' => 'required|string|max:30',
            'suffix' => 'nullable|string|max:5',
            'phone_number' => 'required|string',
        ];

        $isCas = 0;

        if(isset($request->bamaId)) {
            $isCas = 1;
            $request->merge(['username' => $request->bamaId]);
        }
        else {
            $baseValidator['password'] = 'required|string|min:6|confirmed';
        }

        $this->validate($request,$baseValidator);

        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'email' => $request->email,
            'prefix' => $request->prefix,
            'first_name' => $request->first_name,
            'middle_initial' => $request->middle_initial,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'phone_number' => $request->phone_number,
            'verified' => 1,
            'is_cas' => $isCas
        ]); //Retrieving only the email and password data

        $roles = $request['roles']; //Retrieving the roles field
    //Checking if a role was selected
        if (isset($roles)) {

            foreach ($roles as $role) {
            $role_r = Role::where('id', '=', $role)->firstOrFail();
            $user->assignRole($role_r); //Assigning role to user
            }
        }
    //Redirect to the users.index view and display message
        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully added.');
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id) {
        return redirect('users');
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id) {
        $user = null;
        $roles = null;
        if(Auth::user()->hasRole('Admin') || User::all()->count() == 1){
            $user = User::findOrFail($id);
            $roles = Role::get(); //Get all roles
        } else {
            if($id == Auth::id()){
                $user = User::findOrFail($id);
            } else {
                abort(401);
            }
        }

        return view('users.edit', compact('user', 'roles')); //pass user and roles data to view

    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id) {
        $user = null;
        if(Auth::user()->hasRole('Admin') || $id == Auth::id()){
            $user = User::findOrFail($id);
        } else {
            abort(401);
        }

        //Validate name, email and password fields
        $this->validate($request, [
            'password' => 'nullable|string|min:6|confirmed',
            'email' => 'required|string|email|max:50,'.$id,
            'prefix' => 'nullable|string|max:5',
            'first_name' => 'required|string|max:30',
            'middle_initial' => 'nullable|string|max:1',
            'last_name' => 'required|string|max:30',
            'suffix' => 'nullable|string|max:5',
            'phone_number' => 'required|string',
        ]);
        $input = [
            'email' => $request->email,
            'prefix' => $request->prefix,
            'first_name' => $request->first_name,
            'middle_initial' => $request->middle_initial,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'phone_number' => $request->phone_number,
        ];

        if($request->password){
            $input['password'] = bcrypt($request->password);
        }

        $user->fill($input)->save();
        if(Auth::user()->hasRole('Admin') || User::all()->count() == 1){
            $roles = $request['roles']; //Retreive all roles

            if (isset($roles)) {
                $user->roles()->sync($roles);  //If one or more role is selected associate user to roles
            }
            else {
                $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
            }
        }
        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully edited.');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id) {
        //Find a user with a given id and delete
        $user = null;
        if(Auth::user()->hasRole('Admin') || $id == Auth::id()){
            $user = User::findOrFail($id);
        } else {
            abort(401);
        }
        $user->delete();

        return back()->with('flash_message',
             'User successfully deleted.');
    }
    public function downloadOne($id){
      if($id=="All") {
        return UserController::downloadAll();
      }
      $data = User::select('id', 'first_name','last_name', 'email')->where('id',$id)->get();
      return Excel::create('UserExcel', function($excel) use ($data) {
        $excel->sheet('mySheet', function($sheet) use ($data) {
            $sheet->fromArray($data, null, 'A1', false, false);
            $headings = array('', 'First Name','Last Name','Email');
            $sheet->prependRow(1, $headings);
        });
      })->download('xlsx');
    }
    public function downloadAll(){
      $users = User::select('id', 'first_name','last_name', 'email')->get();
      Excel::create('AllUsers', function($excel) use($users) {
      $excel->sheet('Sheet 1', function($sheet) use($users) {
        $sheet->fromArray($users, null, 'A1', false, false);
        $headings = array('', 'First Name','Last Name','Email');
        $sheet->prependRow(1, $headings);
      });
    })->export('xlsx');
  }
public function pdf($id,Request $request){
  if($id=="All") {
    return UserController::pdfAll($request);
  }
  $data =User::select('id', 'first_name','last_name', 'email')->where('id',$id)->get();
  return Excel::create('FormExcel', function($excel) use ($data) {
    $excel->sheet('mySheet', function($sheet) use ($data) {
        $sheet->fromArray($data, null, 'A1', false, false);
        $headings = array('', 'First Name','Last Name','Email');
        $sheet->prependRow(1, $headings);
    });
  })->download('pdf');
}
public function pdfAll(Request $request) {
  /*$query = User::query();
  if(Input::has('first_name')) {
      $query->where('first_name', $getName);
  }
  $users = $query->get();*/
  //$query = $request->all();
  //$query = DB::table('users');
  //$cols = ['First Name', 'Last Name', 'Email'];
  //$oldq = $request->all();
  //$users = Search::searchQuery($request, $cols, $query);

  $users = User::select('id', 'first_name','last_name', 'email')->get();
  Excel::create('AllUsers', function($excel) use($users) {
  $excel->sheet('Sheet 1', function($sheet) use($users) {
      $sheet->fromArray($users, null, 'A1', false, false);
      $headings = array('', 'First Name','Last Name','Email');
      $sheet->prependRow(1, $headings);
      });
    })->export('pdf');
  }



    public function listUnverified(Request $request) {
        if(Auth::user()->hasRole('Admin')) {



            $userTable = new Table;

            $cols = [
              [
                'displayName' => 'First Name',
                'varName' => 'first_name',
                'searchable' => true
              ],
              [
                'displayName' => 'Last Name',
                'varName' => 'last_name',
                'searchable' => true
              ],
              [
                'displayName' => 'Roles',
                'varName' => 'roles',
                'searchable' => true,
                'whereField' => DB::raw(
                              "(
                                SELECT group_concat(roles.name SEPARATOR ', ')
                                FROM users as u2
                                JOIN model_has_roles ON model_has_roles.model_id = u2.id
                                JOIN roles ON model_has_roles.role_id = roles.id WHERE u2.id=users.id
                                )"
                              )
              ],
              [
                'displayName' => 'Approve',
                'varName' => 'approve'
              ],
              [
                'displayName' => 'Deny',
                'varName' => 'deny'
              ],
            ];

            $userTable->addColumns($cols);
            // Table garbage stuff
            $query = DB::table('users');

            // Getting Role info.............
            $query = $query
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->select('users.*', DB::raw("group_concat(name SEPARATOR ', ') as roles"))
                ->groupBy('users.id');

            $query = $query->where('verified', 0)->whereNull('deleted_at');


            if($request->column) {
              if($request->dir){
                $query = $query->orderBy($request->column, $request->dir);
              } else {
                $query = $query->orderBy($request->column);
              }
            }

            $query = $query->paginate(Setting::byName('Pagination'));

            foreach($query as $row) {

              $row->approve = [
                "method" => "post",
                "route" => ["users.verify",$row->id],
                "name" => "Approve",
                "icon" => "thumbs-up",
                "class" => "success"
              ];

              $row->deny = [
                "method" => "DELETE",
                "route" => ["users.destroy",$row->id],
                "name" => "Deny",
                "icon" => "thumbs-down",
                "class" => "danger"
              ];

            }


            $userTable->addRows($query);

            if($query->count() == 0) {
                return redirect()->route('users.index');
            } else {
                return view('users.verify')
                    ->with(['tableData'=> $userTable]);
            }
        }
    }

    public function verify($id) {
        $user = null;
        if(Auth::user()->hasRole('Admin')){
            $user = User::findOrFail($id);
        } else {
            abort(401);
        }
        $user->update(['verified' => 1]);

        Mail::to($user)->send(new AccountVerified($user));

        return redirect()->route('users.showUnverified')
            ->with('flash_message',
             'User successfully Verified!');
    }

  
}
