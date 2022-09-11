<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quizze;
use App\Models\Question;
use App\Models\Option;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Answer;
use App\Models\QuizePermission;

class QuizeController extends Controller
{

    protected function addQuize(){
        $quizze = new Quizze;
        $quizze->name = 'New Quize';
        $quizze->save();
        return redirect()->route('quize_view',$quizze->id);
    }



    protected function quizeView($id){

        $quizze = Quizze::where('id', $id)->where('is_deleted', 0)->first();

        if($quizze){
            return view('admin/quize/quizeView')->with(compact('id'));
        }else{return abort(404);}
    }

    protected function allQuizzes(){
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
       return view('admin/quize/allQuizzes')->with(compact('quizzes'));
    }

    protected function quizeTemplet(Request $request){
        //return 'okk';
        $id = $request->id;

        $quizze = Quizze::where('id', $id)->first();

        // $questions = Question::where('quize_id', $id)->get();
        // foreach($questions as $keyUp => $question){
        //     $options = Option::where('question_id', $question->id)->get();
        //     $questions[$keyUp]['options'] = $options;
        // }$quizze['questions'] = $questions;

        return view('admin.quize.quizeTemplet')->with(compact('quizze'));

    }

    protected function changeQuizeInfo(Request $request){

        $quizze = Quizze::where('id', $request->id)->first();

        if($quizze){
            $quizze->name = $request->name;
            $quizze->start_time = $request->startTime;
            $quizze->end_time = $request->endTime;
            $quizze->duration = $request->duration;
            $quizze->default_mark = $request->defaultMark;
            $quizze->update();
            $questions = Question::where('quize_id',$quizze->id)->get();
            foreach($questions as $question){
                $question->mark = $request->defaultMark;
                $question->update();
            }
        }

        return $this->quizeTemplet($request);
    }

    protected function addQuestion(Request $request){
        $quizze = Quizze::where('id', $request->id)->first();
        if($quizze){
            $question = new Question;
            $question->quize_id = $request->id;
            $question->mark = $quizze->default_mark;
            $question->save();
        }
        return $this->quizeTemplet($request);
    }

    protected function changeQuestionInfo(Request $request){
        $question = Question::where('id', $request->qid)->first();
        if($question){
            $question->question_text = $request->questionText;
            $question->update();
        }
        return $this->quizeTemplet($request);
    }

    protected function addOption(Request $request){
        $option = new Option;
        $option->question_id = $request->qid;
        $option->option_text = "Option $request->count";
        $option->save();
        return $this->quizeTemplet($request);
    }
    protected function changeOptionInfo(Request $request){
        $option = Option::where('id', $request->oid)->first();
        if($option){
            $option->option_text = $request->optionText;
            $option->update();
        }
        return $this->quizeTemplet($request);
    }

    protected function removeQuestion(Request $request){
        $question = Question::where('id', $request->qid)->first();
        if($question){$question->delete();}
        return $this->quizeTemplet($request);
    }

    protected function removeOption(Request $request){
        $option = Option::where('id', $request->oid)->first();
        if($option){$option->delete();}
        return $this->quizeTemplet($request);
    }

    protected function setCorrectOption(Request $request){
        $options = Option::where('question_id', $request->qid)->get();
        foreach($options as $option){
            if($option->id == $request->oid){$option->is_correct = 1;}
            else{ $option->is_correct = 0;}
            $option->update();
        }
        return $this->quizeTemplet($request);
    }

    protected function assignQuize($id){
        $quize = Quizze::where('id', $id)->first();
        if($quize && $quize->status == "created"){
            $start_time = $quize->start_time;
            $end_time = $quize->end_time;
            if($start_time==null) return redirect()->route('quize_view',$id)->with('error', 'Set start time');
            else if($end_time==null) return redirect()->route('quize_view',$id)->with('error', 'Set end time');
            else{
                return view('admin.quize.quizeAssign')->with(compact('id',));
            }
        }else{return abort(404);}
    }


    protected function candidates(Request $request){
        $key = $request->key;
        return $users = User::join('candidates', 'candidates.user_id', '=', 'users.id')
        ->where('users.is_deleted', 0)
        ->where('candidates.status', 'approved')
        ->where( function($query)use ($key){
            if($key != null && $key != ""){
                $con1 = ['users.name', 'like', "%".$key."%"];
                $con2 = ['users.email', 'like', "%".$key."%"];
                $con3 = ['candidates.phone', 'like', "%".$key."%"];
            }else{
                $con1 = ['candidates.id', '!=', 0];
                $con2 = ['candidates.id', '!=', 0];
                $con3 = ['candidates.id', '!=', 0];
            }
            $query->where([$con1])->orWhere([$con2])->orWhere([$con3]);
        })
        ->select('users.id', 'users.name', 'users.email', 'candidates.id as candidate_id', 'candidates.phone', 'candidates.cv', 'candidates.status')
        ->get();
    }


    protected function getCandidates(Request $request){
        $quizeId = $request->id;
        $users = $this->candidates($request);
        $quizePermission = Candidate::join('quize_permissions', 'candidates.id', '=', 'quize_permissions.candidate_id')
            ->where('candidates.status', 'approved')
            ->where('quize_permissions.quize_id', $quizeId)
            ->pluck('candidates.id')->toArray();

        return view('admin.quize.candidateTemplet')->with(compact('users','quizePermission','quizeId'));

    }

    protected function setUserPermission(Request $request){

        $candidates = $this->candidates($request);
        $isSelect = $request->selector;
        foreach($candidates as $candidate){
            $quizePermission = QuizePermission::where('candidate_id',$candidate->candidate_id)
            ->where('quize_id', $request->id)
            ->first();

            if($quizePermission && $isSelect == 'false'){
                $quizePermission->delete();
            }
            else if(!$quizePermission && $isSelect){
                $quizePermission = new QuizePermission;
                $quizePermission->quize_id = $request->id;
                $quizePermission->candidate_id = $candidate->candidate_id;
                $quizePermission->save();
            }
        }
    }

    protected function assignSingleCandidate(Request $request){
        $isSelect = $request->selector;
        $quizeId = $request->quizeId;
        $candidateId = $request->candidateId;

        $quizePermission = QuizePermission::where('candidate_id',$candidateId)
        ->where('quize_id', $quizeId)
        ->first();

        if($quizePermission && $isSelect == 'false'){
            $quizePermission->delete();
        }
        else if(!$quizePermission && $isSelect){
            $quizePermission = new QuizePermission;
            $quizePermission->quize_id = $quizeId;
            $quizePermission->candidate_id = $candidateId;
            $quizePermission->save();
        }
    }

    protected function setQuize(Request $request){

        $quizze = Quizze::where('id', $request->quizeId)->first();
        if($quizze){
            $quizze->status = 'assigned';
            $quizze->save();
            //return view('admin.quize.quizeTemplet')->with(compact('quizze'));
            //return redirect()->route('quize_view',$quizze->id);
        }
    }


    protected function viewQuizeResult(Request $request){
        $candidates = Candidate::join('users','users.id','=','candidates.user_id')
                ->join('quize_permissions', 'quize_permissions.candidate_id','=','candidates.id')
                ->where('quize_permissions.quize_id',$request->id)
                ->where(function($query){
                    $query->where('quize_permissions.status', 'timeovered')
                    ->orwhere('quize_permissions.status', 'submited');
                })
                ->select('candidates.id','users.name','quize_permissions.quize_id')
                ->get();
        return view('admin.quize.quizeSubmission')->with(compact('candidates'));
    }


    protected function viewQuize(Request $request){
        $id = $request->id;

        $candidate = Candidate::where('user_id', auth()->user()->id)->first();
        if(!$candidate){return abort(404);}

        $quizze = Quizze::where('id', $id)->where('is_deleted', 0)->first();

        if($quizze){

            $candidateId = $candidate->id;
            $quizePermission = QuizePermission::where('candidate_id',$candidateId)
                ->where('quize_id', $id)
                ->where(function($query){
                    $query->where('quize_permissions.status', 'assigned')
                    ->orwhere('quize_permissions.status', 'attended')
                        ->orwhere('quize_permissions.status', 'started');
                })
                ->first();
                if(!$quizePermission){return  abort(404);}

            if($quizePermission->start_time == null){
                date_default_timezone_set('Asia/Dhaka');
                $quizePermission->start_time = date('Y-m-d H:i:s');
                $quizePermission->status = 'attended';
                $quizePermission->update();
            }

            $answers = Answer::where('candidate_id', $candidateId)
                        ->where('quize_id', $id)
                        ->pluck('option_id')->toArray();
                        // /return $answers;

            return view('user.quize.quizeView')->with(compact('quizze','id','candidateId','answers', 'quizePermission'));
        }else{return abort(404);}
    }


    protected function setAnswer(Request $request){
        date_default_timezone_set('Asia/Dhaka');
        $answer = Answer::where('candidate_id', $request->candidateId)
                    ->where('quize_id', $request->quizeId)
                    ->where('question_id', $request->questionId)
                    ->first();
        if($answer){
            $answer->option_id = $request->optionId;
            $answer->select_time = date('Y-m-d H:i:s');
            $answer->update();
        }else{


            $answer = new Answer;
            $answer->candidate_id = $request->candidateId;
            $answer->quize_id = $request->quizeId;
            $answer->question_id = $request->questionId;
            $answer->option_id = $request->optionId;
            $answer->select_time = date('Y-m-d H:i:s');

            $answer->save();

        }
    }

    protected function finishQuize(Request $request){
        date_default_timezone_set('Asia/Dhaka');
        $quizeId = $request->id;
        $candidate = Candidate::where('user_id', auth()->user()->id)->first();
        if(!$candidate){return abort(404);}
        
        $quize = Quizze::join('quize_permissions', 'quizzes.id', '=', 'quize_permissions.quize_id')
        ->where('quizzes.id', $quizeId)
        ->where('quize_permissions.candidate_id', $candidate->id)
        ->where(function($query){
            $query->where('quize_permissions.status', 'attended')
            ->orwhere('quize_permissions.status', 'started');
        })
        ->first();
        if($quize){
            $time = date('Y-m-d H:i:s');
            return $this->updateQuizeSubmition($candidate->id, $quize->id, $time, 'timeovered');
        }else{
            return abort(404);
        }

    }



    protected function submitQuize(Request $request){

        date_default_timezone_set('Asia/Dhaka');
        $quizeId = $request->id;

        $quize = Quizze::where('id', $quizeId)->first();
        if($quize){
            $candidate = Candidate::where('user_id', auth()->user()->id)->first();
            $time = date('Y-m-d H:i:s');
            return $this->updateQuizeSubmition($candidate->id, $quize->id, $time, 'submited');
        }else{
            return abort(404);
        }


    }



    private function updateQuizeSubmition($candidateid, $quizeId, $time, $status){
        $result = Answer::join('options', 'options.id', '=', 'answers.option_id')
                        ->join('questions', 'questions.id', '=', 'options.question_id')
                        ->where('answers.candidate_id', $candidateid)
                        ->where('answers.quize_id', $quizeId)
                        ->where('options.is_correct', 1)
                        ->sum('questions.mark');

        $quizePermission = QuizePermission::where('candidate_id', $candidateid)
                            ->where('quize_id', $quizeId)->first();

        $quizePermission->status = $status;
        $quizePermission->submit_time = $time;
        $quizePermission->result = $result;
        $quizePermission->update();

        return $this->quizeHistoryView($quizeId, $candidateid);
    }

    protected function quizeHistory(){
        $candidate = Candidate::where('user_id', auth()->user()->id)->first();
        $quizzes = Quizze::join('quize_permissions', 'quizzes.id', '=', 'quize_permissions.quize_id')
                ->where('quize_permissions.candidate_id', $candidate->id)
                ->where(function($query){
                    $query->where('quize_permissions.status', 'notattend')
                    ->orwhere('quize_permissions.status', 'submited')
                    ->orwhere('quize_permissions.status', 'timeovered');
                })
                ->select('quizzes.id','quizzes.name','quizzes.start_time', 'quizzes.end_time', 'quizzes.duration', 'quizzes.status', 'quize_permissions.candidate_id')->get();
        return view('user.quize.quizeHistory')->with(compact('quizzes'));
    }

    protected function quizeHistoryView($quizeId, $candidateId){
        $candidate = Candidate::where('user_id', auth()->user()->id)->first();
        if(auth()->user()->role == 'admin' || ($candidate && $candidate->id == $candidateId)){
            $quizePermission = QuizePermission::where('candidate_id', $candidateId)
                            ->where('quize_id', $quizeId)
                            ->where(function($query){
                                $query->where('quize_permissions.status', 'notattend')
                                    ->orwhere('quize_permissions.status', 'submited')
                                    ->orwhere('quize_permissions.status', 'timeovered');
                            })->first();
            if(!$quizePermission){return abort(404);}
            $quizze = Quizze::where('id', $quizeId)->first();

            $answers = Answer::where('candidate_id', $candidateId)
                        ->where('quize_id', $quizeId)
                        ->pluck('option_id')->toArray();

            if(auth()->user()->role == 'admin')
            return view('admin.quize.quizeResult')->with(compact('quizze','quizePermission','answers'));
            else return view('user.quize.quizeResult')->with(compact('quizze','quizePermission','answers'));
        }else{
            return abort(404);
        }



    }


}


// $quizze = [ ogo mur priya
//     'name' => 'quizename',
//     'date' => '22/02/2022',
//     'questions' => [
//         1 =>[
//             'question_text' => 'this is question 1',
//             'options' =>[
//                 1 => [
//                     'option_text' => 'option 1',
//                     'is_correct' => 0,
//                 ],
//                 2 => [
//                     'option_text' => 'option 2',
//                     'is_correct' => 0,
//                 ],
//             ],
//         ],
//         2 =>[
//             'question_text' => 'this is question 2',
//             'options' =>[
//                 1 => [
//                     'option_text' => 'option 1',
//                     'is_correct' => 0,
//                 ],
//                 2 => [
//                     'option_text' => 'option 2',
//                     'is_correct' => 0,
//                 ],
//             ],
//         ],
//     ],
// ];
