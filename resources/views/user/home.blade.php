@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD</h2>
    </div>



    <div class="row clearfix">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Upcomming Quize</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover dashboard-task-infos">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Quize Name</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Duration(H:M)</th>
                                    <th>Time Left</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($quizzes as $quize)
                                    <tr onclick="viewQuize({{$quize->id}}, '{{$quize->start_time}}')" style="cursor: pointer;" onMouseOver="this.style.background='#e1dcdc'" onMouseOut="this.style.background=''">
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$quize->name}}</td>
                                        <td class="startTime">{{ date('d M Y h:i A', strtotime($quize->start_time))}}</td>
                                        <td>{{date('d M Y h:i A', strtotime($quize->end_time))}}</td>
                                        <td>{{(int)((int)$quize->duration / (int)60)}}:{{(int)((int)$quize->duration % (int)60)}}</td>
                                        <td id="startTime{{$loop->index}}"></td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

@endsection

@section('js')
    <script>
        function  viewQuize(id,tim){
            var countDate = new Date(tim).getTime();
           // var nowDate = new Date().getTime();
            var nowDate = new Date().toLocaleString('sv-SE', { timeZone: 'Asia/Dhaka' });
            //alert(nowDate);
           // nowDate = new Date(now).getTime();
            //var distanceDate = parseInt(countDate) - parseInt(nowDate);
            if (tim < nowDate) {
                let url = "{{ route('view_quize', ':id') }}";
                url = url.replace(':id', id);
                window.location.href=url;
            }

        }
    </script>



<script>


    var x = setInterval(function() {
        ele = document.getElementsByClassName('startTime');
        for (let i = 0; i < ele.length; i++) {
            setTimeDif(ele[i].innerHTML,'startTime'+i);
        }
    }, 1000);

    function setTimeDif(time,id){
        

        var countDownDate = new Date(time).getTime();

        var now = new Date().toLocaleString('sv-SE', { timeZone: 'Asia/Dhaka' });
        now = new Date(now).getTime();

        var distance = countDownDate - now;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById(id).innerHTML = days + ": " + hours + ": "
        + minutes + ": " + seconds;

        if (distance < 0) {
            // clearInterval(x);
            document.getElementById(id).innerHTML = "Started";
        }
    }
</script>
@endsection
