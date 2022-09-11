<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Candidate;

class UserController extends Controller
{
   protected function singleUser($id){
        $user = User::join('candidates', 'users.id', '=', 'candidates.user_id')
        ->where('users.id', $id)
        ->select('users.id','users.name','users.email','candidates.id as candidate_id', 'candidates.cv' ,'candidates.phone', 'candidates.status')
        ->first();
        if($user){
           return view('admin.user.singleUser')->with(compact('user'));
        }else{return abort(404);}

   }

   protected function aproveUser($id){
        $candidate = Candidate::where('candidates.user_id', $id)->first();
        if($candidate){
            $candidate->status = 'approved';
            $candidate->update();
            return redirect()->route('single_user', $id)->with('success',"This user is Approved");;
        }else{return abort(404);}
    }

    protected function rejectUser($id){
        $candidate = Candidate::where('candidates.user_id', $id)->first();
        if($candidate){
            $candidate->status = 'rejected';
            $candidate->update();
            return redirect()->route('single_user', $id)->with('error',"This user is Rejected");
        }else{return abort(404);}
    }

    protected function deleteUser($id){
        $user = User::where('id', $id)->first();
        if($user){
            $user->is_deleted = 1;
            $user->update();
        }
        return redirect()->route('home');
    }
}
