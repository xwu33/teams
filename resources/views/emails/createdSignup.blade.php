Dear {{ $user->first_name }} {{ $user->last_name }},<br>
<br>
You are signed up to proctor an exam for {{ $exam->course_name }} on {{ date('m/d/Y', strtotime($exam->date)) }} {{ date('g:ia', strtotime($exam->start_time)) }} at {{ $exam->location }}.<br>
<br>
Biological Sciences Graduate Office
