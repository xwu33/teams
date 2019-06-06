<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Search;
use App\Table;
use App\Exam;
use App\Signup;
use App\Setting;
use App\User;
use Auth;

use App\Mail\CancelledExam;


class ExamController extends Controller
{

    public function __construct() {
        $this->middleware(['auth','verified']);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){

      // Table garbage stuff
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
          'varName' => 'taken'
        ],
        [
          'displayName' => 'Date/Time',
          'varName' => 'date_time'
        ],
        [
          'displayName' => "List Proctors",
          'varName' => 'list_proctors'
        ]
      ];

      $examTable->addColumns($cols);

      if(Auth::user()->can("Signup Self")) {
        $signup = [
          'displayName' => 'Sign Up',
          'varName' => 'signup'
        ];
        $examTable->addColumn($signup);
      }
      elseif(Auth::user()->can("Signup All")) {

        $assign = [
          'displayName' => 'Assign',
          'varName' => 'assign'
        ];
        $examTable->addColumn($assign);
      }
      if(Auth::user()->can("Exam All") || Auth::user()->can("Exam Self")) {
        $editDelete = [
          [
            'displayName' => 'Edit',
            'varName' => 'edit'
          ],
          [
            'displayName' => 'Delete',
            'varName' => 'delete'
          ]
        ];
        $examTable->addColumns($editDelete);
      }

      if(Auth::user()->can("Exam All")) {
        $examTable->addColumn([
          'displayName' => "Lock",
          'varName' => 'lock'
        ]);
      }

      $query = DB::table('exams');

      //get number of open seats
      $query = $query
      ->join('users','exams.owner_id','=','users.id');

      $query = Search::searchQuery($request, $examTable->cols, $query);

      $query = $query
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
          ) as date_time"
        ),
        DB::raw("CONCAT(COUNT(signups.user_id),'/',max_proctors) AS taken")
      );

      //get number of signed up students
      $query = $query->leftJoin('signups',function ($join) {
        $join->on('exams.id','=','signups.exam_id')
        ->whereNull('signups.deleted_at');
      });

      $query = $query->groupBy('exams.id');

      $query = $query->whereNull('exams.deleted_at');

      if($request->column) {
        if($request->dir){
          $query = $query->orderBy($request->column, $request->dir);
        } else {
          $query = $query->orderBy($request->column);
        }
      }

      if($request->from_date) {
        $query = $query->where('date','>=', $request->from_date);
      }
      else {
        $query = $query->where('date', '>=', date("Y-m-d 00:00:00"));
      }

      if($request->to_date) {
        $query = $query->where('date','<=', $request->to_date);
      }

      if($request->showFullExams == "open") {
        $query = $query->where("current_signups", "<",DB::raw("max_proctors"));
      }
      elseif($request->showFullExams == "full") {
        $query = $query->where("current_signups", ">=",DB::raw("max_proctors"));
      }

      $query = $query->paginate(Setting::byName('Pagination'));

      // Gets list of students...
      $studentList = User::role('Student')->select(DB::raw("CONCAT_WS(' ',users.first_name,users.last_name) AS name"), 'users.id as id')->get();

      foreach ($query as $row) {

        $row->list_proctors = [
          "method" => "get",
          "route" => ["signups.listExamSignups",$row->id],
          "name" => "List Proctors",
          "class" => "success"
        ];

        if(Auth::user()->can("Signup Self")) {
          $exam = Exam::findOrFail($row->id);
          if(strtotime($exam->date) > time()) {
            $signup = Signup::where([['exam_id',$row->id],['user_id',Auth::user()->id]])->first();
            if($signup != null) {
              $row->signup = [
                "method" => "DELETE",
                "route" => ["signups.destroy",$signup->id],
                "name" => "Cancel Sign Up",
                "class" => "danger"
              ];
            }
            else {
              $row->signup = [
                "method" => "get",
                "route" => ["signups.signupSelf",$row->id],
                "name" => "Sign Up",
                "class" => "success"
              ];
            }
          }
        }

        if(Auth::user()->can("Signup All")) {
          $exam = Exam::findOrFail($row->id);
          if($exam->current_signups < $exam->max_proctors) {
            $row->assign = '
            <button data-examid='.$row->id.'
            class="assignBtn btn btn-success">
            Assign
            </button>
            ';
          }
        }

        if(Auth::user()->can("Exam All") || (Auth::user()->can("Exam Self") && Auth::user()->id == $row->owner_id)) {
          $row->edit = [
            "method" => "get",
            "route" => ["exams.edit",$row->id],
            "name" => "Edit",
            "icon" => "pencil",
            "class" => "warning"
          ];

          $row->delete = [
            "method" => "DELETE",
            "route" => ["exams.destroy",$row->id],
            "name" => "Delete",
            "icon" => "trash",
            "class" => "danger"
          ];



        }
        if(Auth::user()->can("Exam All")) {
          $exam = Exam::findOrFail($row->id);
          if(!$exam->locked) {
            $row->lock = [
              "method" => "post",
              "route" => ["exams.toggleLock",$row->id],
              "name" => "Lock",
              "class" => "danger"
            ];
          } else {
            $row->lock = [
              "method" => "post",
              "route" => ["exams.toggleLock",$row->id],
              "name" => "Unlock",
              "class" => "success"
            ];
          }
        }

      }

      $examTable->addRows($query);

      $currentSemester = Exam::getSemester(true);


      $currentSemesterEndDate = date("Y-m-t",strtotime($currentSemester->year . "-" . $currentSemester->endMonth . "-01"));



      if(Auth::user()->can("Signup All")) {
        return view('exams.index')->with(
          [
            'tableData' => $examTable,
            'options' => $request->all(),
            'currentSemesterEndDate' => $currentSemesterEndDate,
            'studentList' => $studentList
          ]
        );

      } else {
        return view('exams.index')->with(['tableData' => $examTable,'options' => $request->all(),'currentSemesterEndDate'=>$currentSemesterEndDate]);
      }

    }


    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create() {
      if(!(Auth::user()->can('Exam Self') || Auth::user()->can('Exam All'))) {
        return redirect()->route('errors.perms.resource');
      }

      //$years = DB::table('exams')->select('school_year')->groupBy('school_year')->orderBy('school_year', 'desc')->get();

      /*$currentYear = date("Y");
      $currentMonth = date("m");

      if($years->isEmpty()) {
        $obj = new \stdClass;
        $obj->school_year = $currentYear-1;
        $years->prepend($obj);
      }

        while($currentYear > $years[0]->school_year) {
          $obj = new \stdClass;
          $obj->school_year = $years[0]->school_year+1;
          $years->prepend($obj);
        }
      }*/


      //get list of instructors if admin
      $instructors = collect([]);

      if(Auth::user()->can('Exam All')) {
        $instructors = User::permission(['Exam Self','Exam All'])->select('users.*',DB::raw("CONCAT_WS(' ',prefix,first_name,last_name,suffix) AS Instructor"))->get();
      }

      $instructors = $instructors->sortBy(['first_name','last_name']);

      return view('exams.create')->with(['instructors' => $instructors]);
    }


    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request) {

      if(!(Auth::user()->can('Exam All') || Auth::user()->can('Exam Self'))) {
        return redirect()->route('errors.perms.resource');
      }

      $this->validate($request, [
        'instructor' => 'numeric',
        'course_name' => 'required|string|max:30',
        'location' => 'required|string|max:30',
        'date' => 'required|date',
        'start_time' => 'required|date_multi_format:"H:i","H:i:s","H","Ha","H a","H:i a","H:ia","H:i:sa","H:i:s a"',
        'end_time' => 'required|date_multi_format:"H:i","H:i:s","H","Ha","H a","H:i a","H:ia","H:i:sa","H:i:s a"|after:start_time',
        //'max_proctors' => 'required|numeric',
        'max_students' => 'required|numeric',
      ]);
      $owner_id = Auth::id();
      if($request->instructor) {
        $owner_id = $request->instructor;
      }

      $proctor_ratio = Setting::byName('Students per Proctor');
      $max_proctors = (floor($request->max_students/$proctor_ratio) == 0) ? 1 : floor($request->max_students/$proctor_ratio);


      $exam = Exam::create([
        'owner_id' => $owner_id,
        'course_name' => $request->course_name,
        'location' => $request->location,
        'date' => date('Y-m-d H:i:s', strtotime($request->date)),
        'start_time' => date('H:i:s', strtotime($request->start_time)),
        'end_time' => date('H:i:s', strtotime($request->end_time)),
        'max_proctors' => $max_proctors,
        'max_students' => $request->max_students,
      ]);

      if(isset($request->incalendar)){
        return redirect()->route('calendar.index')
        ->with([
          'flash_message' => 'Exam successfully added.',
          'date' => date('Y-m-d', strtotime($request->date)),
          'calView' => $request->calView
        ]);
      } else {
        return redirect()->route('exams.index')
        ->with('flash_message',
        'Exam successfully added.');
      }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id) {
      $exam = null;
      if(Auth::user()->can('Exam All') || Auth::user()->can('Exam Self')){
        $exam = Exam::findOrFail($id);
        if(!Auth::user()->can('Exam All') && Auth::user()->id != $exam->owner_id) {
          return redirect()->route('errors.perms.resource');
        }
      }
      else {
        return redirect()->route('errors.perms.resource');
      }
      if($exam->locked=1 && Auth::user()->cannot("Exam All")) {
        return back()->with('error_message',
        'Error: Exam is locked. Please contact the main office to modify exam.');
      }
      $instructors = collect([]);

      if(Auth::user()->can('Exam All')) {
        $instructors = User::permission(['Exam Self','Exam All'])->select('users.*',DB::raw("CONCAT_WS(' ',prefix,first_name,last_name,suffix) AS Instructor"))->get();
      }

      $instructors = $instructors->sortBy(['first_name','last_name']);

      return view('exams.edit', compact('exam'))->with(["instructors" => $instructors]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id) {
      $exam = null;
      if(Auth::user()->can('Exam All') || Auth::user()->can('Exam Self')){
        $exam = Exam::findOrFail($id);
        if(!Auth::user()->can('Exam All') && Auth::user()->id != $exam->owner_id) {
          return redirect()->route('errors.perms.resource');
        }
      }
      else {
        return redirect()->route('errors.perms.resource');
      }

      if($exam->locked=1 && Auth::user()->cannot("Exam All")) {
        return back()->with('error_message',
        'Error: Exam is locked. Please contact the main office to modify exam.');
      }
      //Validate name, email and password fields
      $this->validate($request, [
        'course_name' => 'required|string|max:30',
        'location' => 'required|string|max:30',
        'date' => 'required|date',
        'start_time' => 'required|date_multi_format:"H:i","H:i:s","H","Ha","H a","H:i a","H:ia","H:i:sa","H:i:s a"',
        'end_time' => 'required|date_multi_format:"H:i","H:i:s","H","Ha","H a","H:i a","H:ia","H:i:sa","H:i:s a"|after:start_time',
        'max_students' => 'required|numeric',
      ]);

      $proctor_ratio = Setting::byName('Students per Proctor');
      $max_proctors = (floor($request->max_students/$proctor_ratio) == 0) ? 1 : floor($request->max_students/$proctor_ratio);

      $input = [
        'course_name' => $request->course_name,
        'location' => $request->location,
        'date' => date('Y-m-d H:i:s', strtotime($request->date)),
        'start_time' => date('H:i:s', strtotime($request->start_time)),
        'end_time' => date('H:i:s', strtotime($request->end_time)),
        'max_proctors' => $max_proctors,
        'max_students' => $request->max_students,
      ];

      if($request->instructor != $exam->owner_id) {
        $input['owner_id'] = $request->instructor;
      }

      $exam->fill($input)->save();
      return redirect()->route('exams.index')
      ->with('flash_message',
      'Exam successfully edited.');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id) {
      //Find a user with a given id and delete
      $exam = null;
      if(Auth::user()->can('Exam All') || Auth::user()->can('Exam Self')){
        $exam = Exam::findOrFail($id);
        if(!Auth::user()->can('Exam All') && Auth::user()->id != $exam->owner_id) {
          return redirect()->route('errors.perms.resource');
        }
      }
      else {
        return redirect()->route('errors.perms.resource');
      }
      if($exam->locked=1 && Auth::user()->cannot("Exam All")) {
        return back()->with('error_message',
        'Error: Exam is locked. Please contact the main office to modify exam.');
      }

      //remove all signups with exam id
      $signups = Signup::where('exam_id', '=', $id)->delete();
      Exam::where('id',$id)->update(['current_signups' => 0]);


      $exam->delete();
      $owner = User::findOrFail($exam->owner_id);
      Mail::to($owner)->send(new CancelledExam($owner, $exam));
      return back()->with('flash_message',
      'Exam successfully deleted.');
    }

    /**
    * Toggled the Locked flag for a given exam
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function toggleLock($id) {
      $exam = null;
      if(Auth::user()->can('Exam All') || Auth::user()->can('Exam Self')){
        $exam = Exam::findOrFail($id);
        if(!Auth::user()->can('Exam All') && Auth::user()->id != $exam->owner_id) {
          return redirect()->route('errors.perms.resource');
        }
      } else {
        return redirect()->route('errors.perms.resource');
      }

      $input = ['locked' => !$exam->locked,];
      $exam->fill($input)->save();

      return back()->with('flash_message',
      ($exam->locked)?'Exam successfully locked.':'Exam successfully unlocked.');

    }

    public function getSemesterList() {

    }

  }
