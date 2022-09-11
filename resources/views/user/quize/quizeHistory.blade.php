@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD / QUIZE HISTORY</h2>
    </div>



    <div class="row clearfix">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Quize History</h2>
                </div>
                <div class="body">

                
                    <p style="padding-left: 10px;"><strong>Quize Name</strong></p>
                    @foreach ($quizzes as $quize)
                    <a href="{{ route('quize_history_view', ['quizeId' => $quize->id, 'candidateId' => $quize->candidate_id]) }}" ><p style="padding-left: 10px; padding-top: 1rem; padding-bottom: 1rem; text-decoration: none !important;" onMouseOver="this.style.background='#e1dcdc'" onMouseOut="this.style.background=''"><span><strong style="padding-right: 10px;">{{$loop->index+1}}</strong></span>{{$quize->name}}</p></a>
                    @endforeach
                </div>
            </div>
        </div>


    </div>
</div>

@endsection
