@extends('layouts.adminApp')
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD/QUIZZES</h2>
    </div>

    <section class="">
        <div class="container-fluid">
            <div class="block-header">
                <h2>All Quizes</h2>
            </div>
            <!-- Basic Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <ul class="header-dropdown m-r--5">
                                <a href="{{route('add_quize')}}" class="btn-sm btn-primary float-right"href="">New Quize</a>
                            </ul>
                        </div>
                        <div class="body table-responsive" id="">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>QUIZE NAME</th>
                                        <th>START</th>
                                        <th>END</th>
                                        <th>DURATION(H:M)</th>
                                        <th>STATUS</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach ($quizzes as $quize)
                                    <tr>
                                        <th scope="row">{{ $loop->index+1 }}</th>
                                        <td>{{ $quize->name }}</td>
                                        <td>{{ date('d M Y h:i A', strtotime($quize->start_time))}}</td>
                                        <td>{{ date('d M Y h:i A', strtotime($quize->end_time))}}</td>
                                        <td>{{ (int)((int)$quize->duration/(int)60) }}:{{ (int)((int)$quize->duration%(int)60) }}</td>
                                        <td>{{ $quize->status }}</td>
                                        <td>
                                            <a href="{{route('quize_view', $quize->id)}}" class="btn btn-primary"><i class="material-icons">visibility</i></a>
                                            {{-- <a class="btn btn-primary">E</a>
                                            <a class="btn btn-primary">D</a> --}}
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
