@extends('layouts.adminApp')
@section('content')

<section class="">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
               Dashboard/Users/Single user

            </h2>
        </div>
        <!-- Basic Validation -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Candidate Info</h2>
                    </div>
                    <div class="body">
                        

                            <div class="body">
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <input type="number" value="{{$user->id}}" hidden >
                                        <div class="form-line">
                                            <label class="">Candidate Name</label>
                                            <p>{{$user->name}}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="">Email</label>
                                        <p>{{$user->email}}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="">Phone</label>
                                        <p>{{$user->phone}}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="">CV</label>
                                        <p><a href="{{$user->cv}}">{{$user->cv}}</a></p>
                                        
                                    </div>

                                    @if($user->status == "registered")
                                    <div class="col-sm-6 ">
                                        <div class="text-end">
                                            <a onClick="confirm('Approve this Candidate?')" href="{{route('approve_user', $user->id)}}" class="btn btn-primary w-100" >Approved</a>
                                            <a onClick="confirm('reject this Candidate?')" href="{{route('reject_user',$user->id)}}" class="btn btn-danger " >Reject</a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    

                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection