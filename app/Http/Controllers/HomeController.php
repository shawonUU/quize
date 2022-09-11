<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Answer;
use App\Models\Quizze;
use App\Models\QuizePermission;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        date_default_timezone_set("Asia/Dhaka");
        $now = date('Y-m-d H:i:s');

        $quizzes = Quizze::where('is_deleted', 0)->get();
        foreach($quizzes as $quize){
            if($quize->status == 'assigned' && $quize->start_time <= $now){
                $quize->status = 'started';
                $quize->update();
            }
            if($quize->status == 'started' && $quize->end_time < $now){
                $quize->status = 'finished';
                $quize->update();
            }
        }



        $quizzeParmissions = Quizze::join('quize_permissions', 'quizzes.id', 'quize_permissions.quize_id')
        ->where('quizzes.status', '!=', 'finished')
        ->where('quize_permissions.status', '!=', 'submitted')
        ->where('quize_permissions.status', '!=', 'timeovered')
        ->where('quize_permissions.status', '!=', 'notattend')
        ->select('quize_permissions.id','quizzes.start_time','quizzes.end_time','quizzes.duration', 'quize_permissions.start_time as quize_start','quize_permissions.status' )
        ->get();
        foreach($quizzeParmissions as $quize){
            if($quize->status == 'assigned' && $quize->end_time < $now){
                $Parmission = QuizePermission::find($quize->id);
                $Parmission->status = 'notattend';
                $Parmission->update();
            }
            else if($quize->quize_start){
                $startTime =  strtotime($quize->quize_start);
                $nowTime =  strtotime($now);
                $startTime += ($quize->duration*60*1000);
                if($startTime < $nowTime){
                    $Parmission = QuizePermission::find($quize->id);
                    $Parmission->status = 'timeovered';
                    $Parmission->update();
                }
            }
        }






        if(auth()->user()->role == 'admin'){
            $users = User::join('candidates', 'candidates.user_id', '=', 'users.id')
            ->where('users.is_deleted', 0)
            ->select('users.id', 'users.name', 'users.email', 'candidates.id as candidate_id', 'candidates.phone', 'candidates.status')
            ->get();
            return view('admin.home')->with(compact('users'));
        }


        if(auth()->user()->role == 'user'){

            $user = User::join('candidates', 'candidates.user_id', '=', 'users.id')
                    ->where('users.id', auth()->user()->id)
                    ->select('candidates.id as candidate_id', 'candidates.status', 'users.is_deleted')
                    ->first();
            if($user->status == 'approved' && $user->is_deleted == 0){
                $quizzes = Quizze::join('quize_permissions', 'quizzes.id', '=', 'quize_permissions.quize_id')
                ->where('quize_permissions.candidate_id', $user->candidate_id)
                ->where(function($query){
                    $query->where('quizzes.status', 'assigned')
                    ->orwhere('quizzes.status', 'started');
                })
                ->where(function($query1){
                    $query1->where('quize_permissions.status', 'assigned')
                    ->orwhere('quize_permissions.status', 'started')
                    ->orwhere('quize_permissions.status', 'attended');
                })
                ->select('quizzes.id','quizzes.name','quizzes.start_time', 'quizzes.end_time', 'quizzes.duration', 'quizzes.status', 'quize_permissions.status as exam_status')->get();
                return view('user.home')->with(compact('quizzes'));
            }
            else{return view('error.notApproved');}
        }

    }

    protected function updateStatus(){

        $quizeparmissions = QuizePermission::join('quizzes', 'quizzes.id', '=', 'quize_permissions.quize_id')
        ->where('status', 'attended')
        ->select('quize_permissions.id', 'quize_permissions.start_time', 'quizzes.duration')
        ->get();

    }

}
