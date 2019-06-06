<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Table;
use App\Exam;
use App\Signup;
use App\Setting;
use App\User;
use Auth;

use App\Mail\CreatedSignup;
use App\Mail\CancelledSignup;

class ExamSignupController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request, $userId = null){


    $examTable = new Table;

    $cols = [
      [
        'displayName' => 'Instructor',
        'varName' => 'instructor',
        'searchable' => true,
        'whereField' => DB::raw('CONCAT_WS(" ",prefix,first_name,last_name,suffix)')
      ],
      [
        'displayName' => 'Course',
        'varName' => 'course_name',
        'searchable' => true
      ],
      [
        'displayName' => 'Location',
        'varName' => 'location',
        'searchable' => true
      ],
      [
        'displayName' => 'Students',
        'varName' => 'max_students'
      ],
      [
        'displayName' => 'Proctors',
        'varName' => 'max_proctors'
      ],
      [
        'displayName' => 'Date/Time',
        'varName' => 'date_time'
      ],
      [
        'displayName' => 'Delete',
        'varName' => 'delete'
      ],
    ];

    $examTable->addColumns($cols);

    // Table garbage stuff
    $query = 0;
    if($userId != null && Auth::user()->can('Signup All')) {
      $query = DB::table('signups')->where('user_id', '=', $userId);
    } elseif ($userId == null && Auth::user()->can('Signup Self')) {
      $query = DB::table('signups')->where('user_id', '=', Auth::id());
    }

    //get number of open seats

    $query = $query
      ->join('exams','exams.id','=','signups.exam_id')
      ->join('users','signups.user_id','=','users.id');

    $query = $query
      ->select(
        'signups.id',
        'exams.course_name',
        'exams.location',
        'exams.max_students',
        'exams.max_proctors',
        DB::raw("CONCAT_WS(' ',prefix,first_name,last_name,suffix) AS instructor"),
        DB::raw("
          CONCAT(
            DATE_FORMAT(date,'%Y-%m-%d'),
            ' from ',
            TIME_FORMAT(start_time, '%I:%i %p'),
            ' to ',
            TIME_FORMAT(end_time, '%I:%i %p')
            ) as date_time")
      );

    $query = $query->whereNull('signups.deleted_at');

    if($request->column) {
      if($request->dir){
        $query = $query->orderBy($request->column, $request->dir);
      } else {
        $query = $query->orderBy($request->column);
      }
    }

    $query = $query->paginate(Setting::byName('Pagination'));

    foreach($query as $row) {

      $row->delete = [
        "method" => "DELETE",
        "route" => ["signups.destroy",$row->id],
        "name" => "Delete",
        "icon" => "trash",
        "class" => "danger"
      ];

    }

    $examTable->addRows($query);


    return view('signups.index')->with(['tableData'=> $examTable]);
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id) {
      //Find a user with a given id and delete
      $signup = null;
      if(Auth::user()->can('Signup All') || Auth::user()->can('Signup Self')){
          $signup = Signup::findOrFail($id);
          if(!Auth::user()->can('Signup All') && Auth::user()->id != $signup->user_id) {
            return redirect()->route('errors.perms.resource');
          }
      }
      else {
        return redirect()->route('errors.perms.resource');
      }
      $exam = Exam::findOrFail($signup->exam_id);

      if(strtotime($exam->date) < time() && Auth::user()->cannot("Exam All")) {
        return back()->with('error_message',
        'Error: You cannot delete a signup from a past Exam.');
      }

      if($exam->locked == 1 && Auth::user()->cannot("Exam All")) {
        return back()->with('error_message',
        'Error: Exam is locked. Please contact the main office to modify exam.');
      }

      DB::table('exams')->where('id',$signup->exam_id)->update(['current_signups' => DB::raw('current_signups-1')]);

      $signup->delete();
      $user = User::findOrFail($signup->user_id);
      Mail::to($user)->send(new CancelledSignup($user, $exam));

      return back()->with('flash_message',
           'Signup successfully removed.');
  }


  /**
  * Used for a student to sign themselves up to a exam.
  *
  * @return \Illuminate\Http\Response
  */
  public function signupSelf($examId) {

    if(!(Auth::user()->can('Signup All') || Auth::user()->can('Signup Self'))) {
      return redirect()->route('errors.perms.resource');
    }
    $exam = Exam::findOrFail($examId);

    if(strtotime($exam->date) < time() && Auth::user()->cannot("Exam All")) {
      return back()->with('error_message',
      'Error: You cannot delete a signup from a past Exam.');
    }

    if($exam->locked == 1 && Auth::user()->cannot("Exam All")) {
      return back()->with('error_message',
      'Error: Exam is locked. Please contact the main office to modify exam.');
    }
    $returnMessage = "";
    $alreadySignedUp = Signup::where([['exam_id',$examId],['user_id',Auth::id()]])->count();

    if($alreadySignedUp == 0) {
      $currentProctors = Signup::where('exam_id', $examId)->count();
      $remainingProctors = $exam->max_proctors - $currentProctors;

      if($remainingProctors > 0) {
        $signup = Signup::create([
          'user_id' => Auth::id(),
          'exam_id' => $examId,
        ]);
        DB::table('exams')->where('id',$examId)->update(['current_signups' => DB::raw('current_signups+1')]);

        Mail::to(Auth::user())->send(new CreatedSignup(Auth::user(), $exam));

        $returnMessage = "You have Successfully been assigned to the exam!";
      } else {
        $returnMessage = "This exam has already reached its max number of signups!";
      }
    } else {
      $returnMessage = "You are already signed up for this exam!";
    }

    return redirect()->back()
    ->with('flash_message', $returnMessage);
  }

  /**
  * Used for a student to sign themselves up to a exam.
  *
  * @return \Illuminate\Http\Response
  */
  public function signupOthers(Request $request, $examId) {
    if(!(Auth::user()->can('Signup All'))) {
      return redirect()->route('errors.perms.resource');
    }

    // still need to check if exam is full or not
    $exam = Exam::findOrFail($examId);

    if($exam->locked == 1 && Auth::user()->cannot("Exam All")) {
      return back()->with('error_message',
      'Error: Exam is locked. Please contact the main office to modify exam.');
    }

    foreach ($request->students as $name => $id) {
      User::role('Student')->findOrFail($id);
      $alreadySignedUp = Signup::where([['exam_id',$examId],['user_id',$id]])->count();

      if($alreadySignedUp == 0) {
        $currentProctors = Signup::where('exam_id', $examId)->count();
        $remainingProctors = $exam->max_proctors - $currentProctors;

        if($remainingProctors > 0 || Auth::user()->can("Exam All")) {
          $user = User::findOrFail($id);
          $signup = Signup::create([
            'user_id' => $id,
            'exam_id' => $examId,
          ]);
          Mail::to($user)->send(new CreatedSignup($user, $exam));
          DB::table('exams')->where('id',$examId)->update(['current_signups' => DB::raw('current_signups+1')]);
        }
      }
    }

    return redirect()->back();
  }

  public function listExamSignups(Request $request,$examId) {

    //get exam info

    $examQuery = DB::table('exams');

    //get number of open seats

    $examQuery = $examQuery
    ->join('users','exams.owner_id','=','users.id');

    $examQuery = $examQuery
    ->select(
      'exams.*',
      DB::raw("CONCAT_WS(' ',prefix,first_name,last_name,suffix) AS instructor"),
      DB::raw("
      CONCAT(
        DATE_FORMAT(date,'%Y-%m-%d'),
        ' from ',
        TIME_FORMAT(start_time, '%I:%i %p'),
        ' to ',
        TIME_FORMAT(end_time, '%I:%i %p')
        ) as date_time")
      );

      $examQuery = $examQuery->where('exams.id',$examId);

      $examQuery = $examQuery->whereNull('exams.deleted_at');

      $examData = $examQuery->first();

      $signupTable = new Table;

      $cols = [
        [
          'displayName' => 'Student',
          'varName' => 'student',
          'searchable' => true,
          'whereField' => DB::raw('CONCAT_WS(" ",prefix,first_name,last_name,suffix)')
        ],
        [
          'displayName' => 'Email',
          'varName' => 'email',
          'searchable' => true,
          'whereField' => 'email'
        ],
      ];

      $signupTable->addColumns($cols);

      if(Auth::user()->can('Signup All')) {

        $signupTable->addColumns([[
          'displayName' => 'Delete',
          'varName' => 'delete'
          ]]);

        }

        $query = DB::table('signups');

        //get number of open seats

        $query = $query
        ->join('users','signups.user_id','=','users.id');

        $query = $query
        ->select('signups.id', 'users.email',
        DB::raw("CONCAT_WS(' ',prefix,first_name,last_name,suffix) AS student")
      );

      $query = $query->where('exam_id',$examId);
      $query = $query->whereNull('signups.deleted_at');
      if($request->column) {
        if($request->dir){
          $query = $query->orderBy($request->column, $request->dir);
        } else {
          $query = $query->orderBy($request->column);
        }
      }


      $query = $query->paginate(Setting::byName('Pagination'));


      foreach($query as $row) {

        $row->delete = [
          "method" => "DELETE",
          "route" => ["signups.destroy",$row->id],
          "name" => "Delete",
          "icon" => "trash",
          "class" => "danger"
        ];

      }

      $signupTable->addRows($query);


      return view('signups.examSignups')->with(['tableData'=> $signupTable,'exam' => $examData]);

    }

  }
