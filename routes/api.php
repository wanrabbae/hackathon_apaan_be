<?php

use App\Http\Controllers\AuthCtrl;
use App\Http\Controllers\QuizCtrl;
use App\Http\Controllers\ViolationCtrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthCtrl::class, 'login']);
Route::post('/register', [AuthCtrl::class, 'register']);
Route::post('/logout', [AuthCtrl::class, 'logout']);

// route group with middleware sanctum
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/quiz', [QuizCtrl::class, 'index']);
    Route::get('/quiz/{id}', [QuizCtrl::class, 'show']);
    Route::post('/quiz', [QuizCtrl::class, 'store']);
    Route::put('/quiz/{id}', [QuizCtrl::class, 'update']);
    Route::delete('/quiz/{id}', [QuizCtrl::class, 'destroy']);

    // QUESTION
    Route::post('/question', [QuizCtrl::class, 'createQuestion']);
    Route::put('/question/{id}', [QuizCtrl::class, 'updateQuestion']);
    Route::delete('/question/{id}', [QuizCtrl::class, 'deleteQuestion']);

    // ANSWER
    Route::post('/answer', [QuizCtrl::class, 'createAnswer']);
    Route::put('/answer/{id}', [QuizCtrl::class, 'updateAnswer']);
    Route::delete('/answer/{id}', [QuizCtrl::class, 'deleteAnswer']);

    // QUIZ RESULT
    Route::get('/quiz-result', [QuizCtrl::class, 'quizResultList']);
    Route::post('/quiz-submit', [QuizCtrl::class, 'submitQuiz']);

    // Violation routes
    Route::post('/violations', [ViolationCtrl::class, 'store']);
    Route::get('/violations/{quiz_id}', [ViolationCtrl::class, 'show']);
});
