Dear {{ $user->first_name }} {{ $user->last_name }},<br>
<br>
This is a reminder that you are scheduled to proctor an exam for {{ $exam->course_name }} on {{ date('m/d/Y', strtotime($exam->date)) }} {{ date('g:ia', strtotime($exam->start_time)) }} at {{ $exam->location }}.<br>
<br>
If you are unable to proctor this exam, you must contact the BSC Graduate Office ASAP.<br>
<br>
Biological Sciences Graduate Office
