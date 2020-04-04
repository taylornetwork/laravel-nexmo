<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\\TaylorNetwork\\LaravelNexmo\\Controllers\\API')
     ->middleware('api')
     ->prefix('nexmo')
     ->as('api.nexmo.')
     ->group(function () {

         Route::any('event/update', 'EventController@handleEventUpdate')->name('event.update');
         Route::any('call/ivr/{ivr}/answer', 'CallController@answerWithIvr')->name('call.ivr.answer');
         Route::any('call/answer', 'CallController@answer')->name('call.answer');

     });