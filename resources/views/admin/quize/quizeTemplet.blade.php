
                    @php 
                        $status = $quizze['status'];
                        $disabled = '';$readonly = '';
                        if($status != 'assigned' && $status != 'created'){
                            $disabled = 'disabled';
                            $readonly = 'readonly';
                        }
                    @endphp
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">

                        <div class="body table-responsive" id="">
                            @if($quizze['status'] == 'created')
                            <div style="float: right;">
                                
                                <a href="{{route('assign_quize', $quizze['id'])}}" class="btn btn-primary" id="assign_quize" href="javaScript:void(0)" >Select Candidats</a>
                            </div>
                            @endif
                            @if($status != 'created' )
                                <div style="float: right;">
                                    <a href="{{route('view_quize_result', $quizze['id'])}}" class="btn btn-primary" id="assign_quize" href="javaScript:void(0)" >Quize Result</a>
                                </div>
                            @endif
                            <div class="body">
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class=" form-float">
                                            <div class="form-line">
                                                <label class="">Quiz Name</label>
                                                <input type="text"  onchange="quizeInfo('{{$quizze['id']}}')" value="{{$quizze['name']}}" id="name" class="form-control"  name="name" required {{$quizze['status'] != 'created' ? 'readonly':''}}>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class=" form-float">
                                            <div class="form-line">
                                                <label class="">Start Time</label>
                                                <input onchange="quizeInfo('{{$quizze['id']}}')" value="{{$quizze['start_time']}}" type="datetime-local" id="start_time" class="form-control"  name="start_time" required {{$quizze['status'] != 'created' ? 'readonly':''}}>
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
                                                <input onchange="quizeInfo('{{$quizze['id']}}')" value="{{$quizze['end_time']}}" type="datetime-local" class="form-control" id="end_time" name="end_time" required {{$quizze['status'] != 'created' ? 'readonly':''}}>
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
                                                <p style="border: 3px solid #ccc;">
                                                    <input id="hours"  onchange="quizeInfo('{{$quizze['id']}}')"  type="number"  min=0 name="value" value="{{(int)((int)$quizze['duration'] / (int)60)}}" size=5/ style="border: none;">
                                                    <span style="font-weight: bold; font-size:18px;">:</span>
                                                    <input id="minutes"  onchange="quizeInfo('{{$quizze['id']}}')" type="number" min=0 name="value" value={{(int)((int)$quizze['duration'] % (int)60)}}  size=5/ style="border: none;">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class=" form-float">
                                            <div class="form-line">
                                                <label class="">Question Mark</label>
                                                <input onchange="quizeInfo('{{$quizze['id']}}')" value="{{$quizze['default_mark']}}" type="number" class="form-control" id="default_mark" name="default_mark" required {{$quizze['status'] != 'created' ? 'readonly':''}}>
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
                               @if($status == 'created' || $status == 'assigned' )
                                <div>
                                    <p style="text-align: end;"><a href="javascript:void(0)"><i onclick="removeQuestion('{{$quizze['id']}}',{{$question->id}})" class="material-icons">close</i></a></p>
                                </div>
                                @endif
                               
                                <textarea  onchange="questionInfo({{$question->id}})"   id="question_text{{$question->id}}" name="question_text{{$question->id}}"   style="height: 25px; width: 100%; border: 0; outline: 0; font-weight: bold; font-size:18px;" {{$readonly}}>{{$question->question_text}}</textarea><br>
                                @foreach($question->options as  $option)
                                   
                                    <div style="margin-top: 2rem;">
                                        <input onchange="setCurrectOption('{{$quizze['id']}}',{{$question->id}}, {{$option->id}})" id="option{{$option->id}}" name="option{{$question->id}}" type="radio" style=" height:20px; width:20px; vertical-align: middle; opacity: 1; position: '' !important;left: 0px !important;" {{ $option->is_correct ? 'checked':''}} {{$disabled}}>
                                        <input for="option{{$option->id}}" onchange="optionInfo({{$option->id}})" id="option_text{{$option->id}}" name="option_text{{$option->id}}" type="text" value="{{$option->option_text}}" style="border: 0; outline: 0; " {{ $readonly}}> <br>
                                        
                                        @if($status == 'created' || $status == 'assigned' )
                                        <div class="float-right" style="float: right; margin-top: -20px;">
                                            <a href="javascript:void(0)"><i onclick="removeOption('{{$quizze['id']}}',{{$option->id}})" class="material-icons" style="font-size: 15px; color: red;">close</i></a>
                                        </div>
                                        @endif
                                       
                                    </div>
                                @endforeach
                                @if($status == 'created' || $status == 'assigned' )
                                <div style="margin-top: 2rem;">
                                    <a onclick="addOption('{{$quizze['id']}}',{{$question->id}},{{count($question['options'])+1}})" href="javaScript:void(0)" >Add Option</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if($status == 'created' || $status == 'assigned' )
                    <div class="d-flex justify-content-end " style="margin-bottom: 2rem !important;">
                        <a onclick="addQuestion('{{$quizze['id']}}')" class="btn btn-primary ">Add Question</a>
                    </div>
                    @endif
                </div>


                <!-- @php
                    echo '<pre>';
                    print_r($quizze);
                @endphp -->
