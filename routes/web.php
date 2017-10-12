<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', ['uses' => 'HomeController@root', 'as' => 'root']);

Route::group(['prefix' => 'laravel', 'middleware' => 'google_recaptcha'], function() {
  Route::get('/', ['uses' => 'HomeController@index', 'as' => 'home']);
  Auth::routes();

  Route::group(['prefix' => 'r'], function() {
    Route::get('/js.js', ['uses' => 'ResourceController@js', 'as' => 'js']);
    Route::get('/questions.js', ['uses' => 'ResourceController@questions', 'as' => 'questions']);
    Route::get('/start-survey.js', ['uses' => 'ResourceController@startSurvey', 'as' => 'start-survey']);
    Route::get('/manage-survey.js', ['uses' => 'ResourceController@manageSurvey', 'as' => 'manage-survey']);
    Route::get('/css.css', ['uses' => 'ResourceController@css', 'as' => 'css']);
  });

  Route::group(['prefix' => 'survey'], function() {
    Route::get('/{uuid}', ['uses' => 'PublicSurveyController@show', 'as' => 'public_survey.show']);
  });

  Route::group(['prefix' => 'api', 'middleware' => 'api'], function() {
    Route::post('/{s_uuid}/session_id', ['uses' => 'APIController@getSessionId']);
    Route::post('/save_answer', ['uses' => 'APIController@saveSurveyAnswer']);
  });

  Route::group(['middleware' => 'auth', 'prefix' => 'dashboard'], function() {
    Route::get('/', ['uses' => 'DashboardController@getDashboard', 'as' => 'dashboard']);

    Route::group(['prefix' => 'survey'], function() {
      Route::get('/create', ['uses' => 'SurveyController@create', 'as' => 'survey.create']);
      Route::post('/create', ['uses' => 'SurveyController@store', 'as' => 'survey.store']);

      Route::get('/{s_uuid}/stats', ['uses' => 'SurveyController@stats', 'as' => 'survey.stats']);
      Route::get('/{s_uuid}/delete', ['uses' => 'SurveyController@destroy', 'as' => 'survey.destroy']);

      Route::get('/{s_uuid}/edit', ['uses' => 'SurveyController@edit', 'as' => 'survey.edit']);
      Route::post('/{s_uuid}/edit', ['uses' => 'SurveyController@update', 'as' => 'survey.update']);

      Route::get('/{s_uuid}/run', ['uses' => 'SurveyController@run', 'as' => 'survey.run']);
      Route::get('/{s_uuid}/pause', ['uses' => 'SurveyController@pause', 'as' => 'survey.pause']);

      Route::get('/{s_uuid}/question/create', ['uses' => 'QuestionController@create', 'as' => 'question.create']);
      Route::post('/{s_uuid}/question/create', ['uses' => 'QuestionController@store', 'as' => 'question.store']);

      Route::get('/{s_uuid}/question/{q_uuid}/delete', ['uses' => 'QuestionController@delete', 'as' => 'question.delete']);

      Route::get('/{s_uuid}/question/{q_uuid}/edit', ['uses' => 'QuestionController@edit', 'as' => 'question.edit']);
      Route::post('/{s_uuid}/question/{q_uuid}/edit', ['uses' => 'QuestionController@update', 'as' => 'question.update']);

      Route::get('/{s_uuid}/change_order', ['uses' => 'QuestionController@showChangeOrder', 'as' => 'question.show_change_order']);
      Route::post('/{s_uuid}/change_order', ['uses' => 'QuestionController@storeChangeOrder', 'as' => 'question.store_change_order']);
    });
  });
});

