
@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD/ QUIZE</h2>
    </div>

    <section class="">
        <div class="container-fluid">
            <div class="block-header">

            </div>
            <!-- Basic Table -->

            <div id="templet_view" class="row clearfix">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">

                        <div class="body table-responsive" id="">

                            <div class="body">
                                <div class="row clearfix " style="margin-bottom: 2rem !important;">
                                    <div class="col-sm-12">
                                        <div class=" form-float ">
                                            <div class="form-line">
                                                {{-- <label class="">Quiz Name</label> --}}
                                                <input  id="name" class="form-control"  name="name" type="text" value="{{$quizze['name']}}" style="border: 0; outline: 0; text-align: center; font-weight: bold; font-size: 1.8rem; " readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class=" form-float">
                                            <div class="form-line">
                                                <label class="">Start Time</label>
                                                <input style="outline: 0; border: 0;" value="{{$quizze['start_time']}}" type="datetime-local" class="form-control" id="start_time"   name="start_time" required readonly>
                                                @error('start_time')
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class=" form-float">
                                            <div class="form-line">
                                                <label class="">End Time</label>
                                                <input style="border: 0; outline: 0; " value="{{$quizze['end_time']}}" type="datetime-local" class="form-control" id="end_time" name="end_time" required readonly>
                                                @error('end_time')
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class=" form-float">
                                            <div class="form-line">
                                                <label class="">Duration(H:M)</label>
                                                <p style="border: 0; outline: 0; ">
                                                    {{(int)((int)$quizze['duration'] / (int)60)}}:{{(int)((int)$quizze['duration'] % (int)60)}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class=" form-float">
                                            <div class="form-line">
                                                <label class="">Time Left</label>
                                                <input id="time_left" style="border: 0; outline: 0; " type="text" class="form-control" id="default_mark" name="default_mark" required {{$quizze['status'] != 'created' ? 'readonly':''}} readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    @foreach($quizze->questions as $question)
                    <div class="card">
                        <div class="body table-responsive" id="">
                            <div class="body">

                                <p style="height: 25px; width: 100%; border: 0; outline: 0; font-weight: bold; font-size:18px;">{{$question->question_text}}</p><br>
                                @foreach($question->options as  $option)
                                    <div style="margin-top: 2rem;">
                                        <input onchange="setAnswer({{$candidateId}},'{{$quizze['id']}}',{{$question->id}}, {{$option->id}})" id="option{{$option->id}}" name="option{{$question->id}}" type="radio" style=" height:20px; width:20px; vertical-align: middle; opacity: 1; position: '' !important;left: 0px !important;" {{ in_array($option->id,$answers) ? 'checked':''}} >
                                        <input for="option{{$option->id}}" name="option_text{{$option->id}}" type="text" value="{{$option->option_text}}" style="border: 0; outline: 0; "> <br>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="" style="float: right; padding-bottom: 2rem;"><a href="{{route('submit_quize', $quizze['id'])}}" class="btn btn-success mb-3" style="font-size: 2rem;">Submit</a></div>


                </div>


                <!-- @php
                    echo '<pre>';
                    print_r($quizze);
                @endphp -->

            </div>
            <!-- #END# Basic Table -->

        </div>
    </section>

</div>
{{-- <input class="html-duration-picker" data-hide-seconds> --}}

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script>
   (function () {
        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        };
    })();
</script> -->
<script>
    function setAnswer(candidateId,quizeId,questionId,optionId){
        $.get('{{route('set_answer')}}',{candidateId:candidateId,quizeId:quizeId,questionId:questionId,optionId:optionId}, function(data){
                // console.log(data);
        });
    }
</script>

<script>
    var x = setInterval(function() {
        setTimeDif();
    }, 1000);

    function setTimeDif(){
        let startTime = '{{ date('d M Y H:i:s', strtotime($quizePermission->start_time))}}';

        let duration = '{{$quizze['duration']}}';
        duration = parseInt(duration);

        var countDownDate = new Date(startTime).getTime();
        var quizeEndTime= new Date('{{$quizze['end_time']}}').getTime();


        var now = new Date().toLocaleString('sv-SE', { timeZone: 'Asia/Dhaka' });

        now = new Date(now).getTime();


        var quizeEndTimeDistance = quizeEndTime - now;
        var distance = countDownDate+(duration*60*1000) - now;
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

       document.getElementById('time_left').value = days + ": " + hours + ": "
        + minutes + ": " + seconds;


        if (distance < 0 || quizeEndTimeDistance < 0) {
            let quizeId = '{{$quizze['id']}}';
            let url = "{{ route('finish_quize', ':id') }}";
            url = url.replace(':id', quizeId);
            window.location.href=url;
            //alert(quizeEndTime);
        }
    }
</script>
@endsection
