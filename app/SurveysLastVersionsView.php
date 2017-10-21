<?php

namespace App;
use Eloquent;

class SurveysLastVersionsView extends Eloquent {
  protected $table = 'surveys_last_version_view';

  public static function getById($survey_id) {
    return (
      $last_version = SurveysLastVersionsView::where('survey_id', '=', $survey_id)
        ->limit(1)
        ->get()
        ->all()
    ) &&
      is_array($last_version) &&
      count($last_version) === 1
        ? $last_version[0]
        : null
    ;
  }
}
