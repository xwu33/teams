<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MaddHatter\LaravelFullcalendar\Facades\Calendar;
use Input;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Table;
use App\Nav;
use App\Search;
use App\Exam;
use App\Signup;
use \ReCaptcha\ReCaptcha;
use Auth;
use Excel;
use Carbon\Carbon;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;
class ExamCalendarController extends Controller
{
    //
    public function __construct() {
    $this->middleware(['auth','verified']);
  }

  public function index(Request $request) {
        $events = [];
        $data = Exam::all();
        if($data->count()){
            foreach ($data as $key => $value) {
                $date = new \DateTime($value->date);
                $formatted = $date->format('Y-m-d');
                $signup_count = Signup::where('exam_id',$value->id)->count();
                //check for edit permissions
                if(Auth::user()->can('Exam All') || Auth::user()->can('Exam Self') && Auth::user()->id == $value->owner_id) {
                  $can_edit = true;
                }
                else {
                  $can_edit = false;
                }

                if(Auth::user()->can('Signup Self') && strtotime($value->date) > time()) {
                  $can_signup = true;
                }
                else {
                  $can_signup = false;
                  //if it's full should be set to yellow -> maybe it should actually be in the function below
                }

                $user_signup = Signup::where([['user_id','=',Auth::user()->id],['exam_id','=',$value->id]])->get();

                if($user_signup->count() > 0) {
                  $is_signed_up = true;
                  $signup_id = $user_signup->first()->id;
                }
                else {
                  $is_signed_up = false;
                  $signup_id = 0;
                }
                $fade='1';
                //This stuff below is the time check + if it's set to full already it should be yellow and ignore the blue
                if((time()-(60*60*24)) < strtotime($value->date)) {
                  if($is_signed_up){
                    $color='green';
                  }
                  elseif($value->max_proctors<=$signup_count){
                    $color='red';
                  }
                  else{
                    $color='#7297e6';
                  }
                }
                //if it isn't full the priority is red -> should be red
                else {
                  $color='#7297e6';
                  $fade='0.2';
                }

                $events[] = Calendar::event(
                    $value->course_name,
                    null,
                    $formatted.' '.$value->start_time,
                    $formatted.' '.$value->end_time,
                    $value->id,
                    [
                        'description' => $value->desc,
                        'can_edit' => $can_edit,
                        'can_signup' => $can_signup,
                        'is_signed_up' => $is_signed_up,
                        'self_signup_id' => $signup_id,
                        'max_proctors' => $value->max_proctors,
                        'signup_count' => $signup_count,
                        'max_students' => $value->max_students,
                        //you add the color in the options array here
                        'color'=> $color,
                        'opacity'=> $fade,
                        'url' => 'http://full-calendar.io',
                    ]
                );
            }
        }

        $calendar = Calendar::addEvents($events)->setCallbacks([
            'eventClick' => 'function (ev) { eventClicked(ev); }',
            'dayClick' => 'function (date, ev, view) { dayClicked(date, ev, view); }'
        ]);

        if(session('date') !== null && session('calView') !== null) {
          $calendar->setOptions([
            'defaultDate' => session('date'),
            'defaultView' => session('calView')
          ]);
        }

        $years = DB::table('exams')->select('school_year')->groupBy('school_year')->orderBy('school_year', 'desc')->get();

        $currentYear = date("Y");
        $currentMonth = date("m");

        if($years->isEmpty()) {
          $obj = new \stdClass;
          $obj->school_year = $currentYear-1;
          $years->prepend($obj);
        }

        if($currentYear > $years[0]->school_year && $currentMonth >= 8) {
          while($currentYear > $years[0]->school_year) {
            $obj = new \stdClass;
            $obj->school_year = $years[0]->school_year+1;
            $years->prepend($obj);
          }
        }

        // Gets list of students for the assign modal
        $studentList = User::role('Student')->select(DB::raw("CONCAT_WS(' ',users.first_name,users.last_name) AS name"), 'users.id as id')->get();



        return view('calendar.index', compact('calendar', 'years','studentList'));
    }
  public function create(){
    $formMaker= new Form;
    $formMaker->addText('title', 'Name of Event');
    $formMaker->addText('desc', 'Event Description');
    $formMaker->addDate('start_time','Y-m-d','Start(yyyy-mm-dd)');
    $formMaker->addDate('end_time','Y-m-d','End(yyyy-mm-dd)');
    return view('calendar.create')->with(['formData'=> $formMaker->render()]);
  }
  public function edit($id)
  {
      $form=Exam::find($id);
      return view('calendar.edit', compact('form'));
  }
  public function store(Request $request)
  {
     //Validate name, email and password fields
            $this->validate($request, [
                'title' => 'required|string|max:30',
                'desc' => 'string|max:1000',
                'start_date' => 'required|date_format:Y-m-d|after:tomorrow',
                'end_date'=> 'required|date_format:Y-m-d|after:start_date',
            ]);
            $form = Event::create([
                'title' => $request->title,
                'desc' => $request->desc,
                //'allday' => $request->allday,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]); //Retrieving only the email and password data
            return redirect()->route('calendar.index')->with('flash_message','Event successfully added.');
  }

  public function update(Request $request, $id)
  {
        $form = Exam::findOrFail($id);
        //Validate name, email and password fields

        $this->validate($request, [
          'title' => 'required|string|max:30',
          'desc' => 'string|max:1000',
          'start_date' => 'required|date_format:Y-m-d|after:tomorrow',
          'end_date'=> 'required|date_format:Y-m-d|after:start_date',
        ]);

        $input = [
          'title' => $request->title,
          'desc' => $request->desc,
          'start_date' => $request->start_date,
          'end_date' => $request->end_date,
        ];
        $form->fill($input)->save();
        return redirect()->route('calendar.index')->with('flash_message','Event successfully edited.');
  }
  public function destroy($id)
  {
    $event = null;
    $event = Exam::findOrFail($id);
    $event->delete();
    return redirect()->route('calendar.index')->with('flash_message','Event successfully deleted.');
  }

}
