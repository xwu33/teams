<div class="col-sm-6 col-md-4">
  <div class="panel panel-default">
    <div class="panel-heading">Filter Results</div>
    <div class="panel-body text-center">

      <form method="GET" action="{{route($route)}}">

        <div class="row">
          <label for="from_date" class="">From:</label>
          <input type="text" alt="From Date" id="from_date" name="from_date" size="12" required
          @if(isset($options['from_date']))
            value="{{$options['from_date']}}"
          @else
            value="{{date("Y-m-d")}}"
          @endif
          />
        </div>
        <div class="row">
          <label for="to_date" class="">To:</label>
          <input type="text" alt="To Date" id="to_date" name="to_date" size="12" required
          @if(isset($options['to_date']))
            value="{{$options['to_date']}}"
          @else
            value="{{$currentSemesterEndDate}}"
          @endif
          />
        </div>



        <div class="row">
          <select id="showFullExams" name="showFullExams">
            <option value="all">Show All Exams</option>
            <option value="open"
            @if((!isset($options['showFullExams']) && (Auth::user()->can("Exam All") || Auth::user()->can("Exam Self"))) || isset($options['showFullExams']) && $options['showFullExams'] == "open")
              selected="selected"
            @endif
            >Show Open Exams</option>
            <option value="full"
            @if(isset($options['showFullExams']) && $options['showFullExams'] == "full")
              selected="selected"
            @endif
            >Show Full Exams</option>
          </select>
          <label for="showPastExams" class="sr-only">Show Full Exams</label>
        </div>

        @foreach($options as $option => $value)
          @if($option == "from_date" || $option == "showFullExams" || $option == "to_date")
            @continue
          @endif
          <input type="hidden" name="{{$option}}" value="{{$value}}" />
        @endforeach

        <div class="row">
          <button type="submit" id="filterButton" name="filterButton" class="btn-warning">Filter Results</button>
        </div>

      </form>

    </div>

  </div>
</div>
