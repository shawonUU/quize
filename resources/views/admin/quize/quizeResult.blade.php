@extends('layouts.adminApp')

@section('content')

<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD/ QUIZE RESULT</h2>
    </div>

    <section class="">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Create New Quizes</h2>
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
                                                <label class="">Attend Time</label>
                                                <input style="outline: 0; border: 0;" value="{{$quizePermission->start_time}}" type="datetime-local" class="form-control" id="start_time"   name="start_time" required readonly>
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
                                                <label class="">Submit Time</label>
                                                <input style="border: 0; outline: 0; " value="{{$quizePermission->submit_time}}" type="datetime-local" class="form-control" id="end_time" name="end_time" required readonly>
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
                                                <label class="">Mark</label>
                                                <input value="{{$quizePermission->result}}" id="time_left" style="border: 0; outline: 0; " type="text" class="form-control" id="default_mark" name="default_mark" required {{$quizze['status'] != 'created' ? 'readonly':''}} readonly>
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
                                    <div style="padding-top:10px; padding-left:10px; margin-top: 2rem; display: flex" class="{{$option->is_correct == 1 && in_array($option->id,$answers) ? 'bg-success' : ''}} {{in_array($option->id,$answers) && $option->is_correct == 0 ? 'bg-danger' : ''}}">
                                        <input  type="radio" style="margin-right: 10px; height:20px; width:20px; vertical-align: middle; opacity: 1; position: '' !important;left: 0px !important;" {{ in_array($option->id,$answers) ? 'checked':''}} disabled>
                                        <p   type="text" value="{{$option->option_text}}" style="border: 0; outline: 0; " readonly>{{$option->option_text}}</p> <br>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                    @endforeach


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
