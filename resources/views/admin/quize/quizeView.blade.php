@extends('layouts.adminApp')

@section('content')

<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD/ ALL QUIZZES/ QUIZE</h2>
    </div>

    <section class="">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Create New Quizes</h2>
            </div>
            <!-- Basic Table -->

            <div id="templet_view" class="row clearfix">

            </div>
            <!-- #END# Basic Table -->

        </div>
    </section>

</div>
{{-- <input class="html-duration-picker" data-hide-seconds> --}}

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
   var id = {{$id}};
  $.get('{{route('quize_templet')}}', {id}, function(data){
        // console.log(data);
        document.getElementById('templet_view').innerHTML = data;
  });

</script>

<script>
    function quizeInfo(id){
        let name = document.getElementById('name').value;
        let startTime = document.getElementById('start_time').value;
        let endTime = document.getElementById('end_time').value;

        if(startTime != null && startTime != ""){
            startTime = startTime.replace(/T/g, " ");
            startTime = startTime+":00";
        }
        if(endTime != null && endTime != ""){
            endTime = endTime+":00";
            endTime = endTime.replace(/T/g, " ");
        }

        let hour = document.getElementById('hours').value;
        let minut = document.getElementById('minutes').value;
        hour = parseInt(hour);
        minut = parseInt(minut);

        if(hour<0) hour = 0;
        if(minut<0) minut = 0;
        let duration = ((hour*60) + minut);


        let defaultMark = document.getElementById('default_mark').value;

        $.get('{{route('change_quize_info')}}', {id:id, name:name, startTime:startTime, endTime:endTime, duration:duration, defaultMark:defaultMark}, function(data){
            document.getElementById('templet_view').innerHTML = data;
        });
    }
</script>
<script>
    function addQuestion(id){

        $.get('{{route('add_question')}}', {id:id}, function(data){
            document.getElementById('templet_view').innerHTML = data;

        });
    }

    function questionInfo(qid){
        let questionText = document.getElementById('question_text'+qid).value;

        $.get('{{route('change_question_info')}}', {qid:qid, questionText:questionText}, function(data){
            document.getElementById('templet_view').innerHTML = data;
        });
    }

    function addOption(id, qid, count){
        $.get('{{route('add_option')}}', {id:id, qid:qid, count:count}, function(data){
            document.getElementById('templet_view').innerHTML = data;
        });
    }

    function optionInfo(oid){

        let optionText = document.getElementById('option_text'+oid).value;
        $.get('{{route('change_option_info')}}', {oid:oid, optionText:optionText}, function(data){
            document.getElementById('templet_view').innerHTML = data;
        });
    }


    function removeQuestion(id, qid){

        $.get('{{route('remove_question')}}', {id:id, qid:qid}, function(data){
            document.getElementById('templet_view').innerHTML = data;
        });
    }

    function removeOption(id, oid){
        $.get('{{route('remove_option')}}', {id:id, oid:oid}, function(data){
            document.getElementById('templet_view').innerHTML = data;
        });
    }

    function setCurrectOption(id, qid, oid){
        $.get('{{route('set_correct_option')}}', {id:id, qid:qid, oid:oid}, function(data){
            document.getElementById('templet_view').innerHTML = data;
        });
    }

    



</script>



@endsection
