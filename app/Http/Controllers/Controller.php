<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function whichSemester() {
      if(strtotime(date('Y-m-d')) > strtotime(date('Y-06-05'))) {
        if(strtotime(date('Y-m-d')) > strtotime(date('Y-08-05'))) {
          return 'fall';
        } else {
          return 'summer';
        }
      } else {
        return 'spring';
      }
    }

    public function endOfSemester() {
      switch ($this->whichSemester()) {
        case 'spring':
          return date('Y-06-05');
          break;
        case 'summer':
          return date('Y-08-05');
          break;
        case 'fall':
          return date('Y-12-15');
          break;
        default:
          return false;
          break;
      }
    }

    public function getData()
    {
      $data['data']=DB::table('discussions')->get();

      if(count($data)>0)
      {
          return view('insertForm', $data);
      }
      else
        {
           return view('insertForm');
        }

    }

    public function insert(Request $req)
    {
      $title = $req->input('title');
      $content = $req->input('content');

      $data = array('title'=>$title, 'content'=>$content);

      DB::table('discussions')->insert($data);

      return view("comments");

    }

    public function delete($id)
    {
      DB::table('discussions')->where('id',$id)->delete();
      return redirect('/getData');
    }


}
