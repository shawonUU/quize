@extends('layouts.adminApp')
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD / ALL QUIZZES / QUIZE / QUIZE ASSIGN </h2>
    </div>

    <section class="">
        <div class="container-fluid">
            <div class="block-header">
                <h2>All Users</h2>
            </div>

            <div  class="row clearfix">
                <div id="" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <div class="d-flex justify-content-end">
                                <a style="float: right; margin-bottom: 10px;" href="javaScript:void(0)" onclick="assignQuize({{$id}})" class="btn btn-success">Assign Quize</a>
                            </div><div></div>
                            <div>
                                <label for="">Search</label>
                                <input onkeyup="getCandidates()"  id="search" type="search" placeholder="search" class="form-control ">
                            </div>
                        </div>
                        <div class="body table-responsive" id="candidates">
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Basic Table -->

        </div>
    </section>

</div>

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function getCandidates(){
        let id = {{$id}};
        let key = document.getElementById('search').value;
        if(key=="")key = null;
        $.get('{{route('get_candidates')}}', {key:key,id:id}, function(data){
            document.getElementById('candidates').innerHTML = data;
        });
    }
    getCandidates();
</script>

<script>
    function selectUser(){
        let id = {{$id}};
        let users = document.getElementsByClassName("user");
        let selector = document.getElementById('select_all').checked;
        let key = document.getElementById('search').value;
        for (let i = 0; i < users.length; i++) {users[i].checked = selector;}
        $.get('{{route('set_user_permission')}}', {id:id,selector:selector,key:key}, function(data){

        });
    }
</script>
<script>
    function assignQuizeToCandidate(id,candidateId,quizeId){
        let selector = document.getElementById(id).checked;
        $.get('{{route('assign_single_candidate')}}', {selector:selector,candidateId:candidateId,quizeId:quizeId}, function(data){

        });
    }
</script>

<script>
    function assignQuize(quizeId){
        if(confirm('Are you sure will assign this quiz? after assigned you can\' select any candidate again.!')){
            $.get('{{route('set_quize')}}', {quizeId:quizeId}, function(data){
                // document.getElementById('templet_view').innerHTML = data;
                // alert('ss');
                let url = "{{ route('quize_view', ':id') }}";
                url = url.replace(':id', quizeId);
                window.location.href=url;
            });
        }
    }
</script>
@endsection



