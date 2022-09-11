@extends('layouts.adminApp')
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD</h2>
    </div>

    <section class="">
        <div class="container-fluid">
            <div class="block-header">
                <h2>All Users</h2>


            </div>
            <!-- Basic Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">

                        </div>
                        <div class="body table-responsive" id="">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NAME</th>
                                        <th>EMAIL</th>
                                        <th>PHONE</th>
                                        <th>STATUS</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach ($users as $user)
                                    <tr>
                                        <th scope="row">{{ $loop->index+1 }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->status }}</td>
                                        <td style="display: flex !important;">
                                            <a href="{{route('single_user', $user->id)}}" class="btn btn-primary" style="margin-left: 10px !important;"><i class="material-icons">visibility</i></a>
                                            <a class="btn btn-warning mx-2" style="margin-left: 10px !important;"><i class="material-icons" >edit</i></a>
                                            <form id="{{$user->id}}" action="{{route('delete_user', $user->id)}}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <a onclick="deleteUser({{$user->id}})" href="javascript:void(0)" class="btn btn-danger mx-2" style="margin-left: 10px !important;"><i class="material-icons" >delete</i></a>
                                            </form>



                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
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
<script>
    function deleteUser(id){
        if(confirm('Delete user?')){
            $('#'+id).submit();
        }
    }
</script>
@endsection
