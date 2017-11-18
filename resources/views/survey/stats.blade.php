@extends('main')

@section('title', '/ Survey statistics')

@section('content')
  <h1 class="title m-b-md text-center">
    Statistics
  </h1>

  <div class="row">
    <div class="col-xs-12 stats-container">
      <div class="row">
        <div class="col-xs-12">
          Total answers: {{ $survey->total_answers }} ({{ $survey->{'fully_answered_%'} }} fully answered)
        </div>
      </div>

      <div class="text-center svg-container">
        <span class="svg-loader">Loading graph...</span>
      </div>

      @foreach($survey->versions as $version)
      <div class="row stats-version-container hide">
        <div class="col-xs-12">
          Version: {{ $version['version'] }}
        </div>
        <div class="col-xs-12">
          <ul>
            <li>Answers: {{ count($version['answers_sessions']) }} ({{ $version['fully_answered_%'] }} fully answered)</li>
          </ul>
        </div>
      </div>
      @endforeach
    </div>

    <div class="col-xs-12">
      <div class="row">
        <div class="pull-right col-sm-4 col-xs-12 form-group">
          {{ Html::linkRoute('survey.edit', 'Back', [$survey->uuid], ['class' => 'btn-block btn btn-default']) }}
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    var $survey_d3_data_json = {!! $survey_d3_data_json !!}
  </script>
  <script type="text/javascript" src="{{ Helper::route('stats') }}"></script>
@endsection

