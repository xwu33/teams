Dear {{ $user->first_name }} {{ $user->last_name }},<br>
<br>
The following students are signed up to proctor the exam for {{ $exam->course_name }} on {{ date('m/d/Y', strtotime($exam->date)) }} {{ date('g:ia', strtotime($exam->start_time)) }} at {{ $exam->location }}.<br>
<br>
@foreach ($proctors as $proctor)
  {{$proctor}}<br>
@endforeach
<br>
<br>
Biological Sciences Graduate Office
