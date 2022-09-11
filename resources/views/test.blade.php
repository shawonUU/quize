


@foreach ($quizze->questions as $question)
   <h1> {{$question->question_text}}</h1>
    @foreach ($question->options as $option)
        <p>{{$option->option_text}}</p>
    @endforeach
@endforeach
