Dear {{ $user->first_name }} {{ $user->last_name }},<br>
<br>
Your proctoring session for {{ $exam->course_name }} on {{ date('m/d/Y', strtotime($exam->date)) }} {{ date('g:ia', strtotime($exam->start_time)) }} has been canceled.<br>
<br>
Biological Sciences Graduate Office
