<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('home');
});
// Route::get('/admin', function () {

//     return view('auth/adminRegister');
// });
Route::get('/admin', [App\Http\Controllers\Auth\RegisterController::class, 'adminRegister'])->name('admin');

// Route::get('/test', [App\Http\Controllers\QuizeController::class, 'test']);

// Route::get('/test', function () {
//     return view('admin/quize/quizeTemplet');
// });

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



    Route::middleware(['IsAdmin'])->group(function () {
        Route::get('/single_user{id}', [App\Http\Controllers\UserController::class, 'singleUser'])->name('single_user');
        Route::get('/approve_user{id}', [App\Http\Controllers\UserController::class, 'aproveUser'])->name('approve_user');
        Route::get('/reject_user{id}', [App\Http\Controllers\UserController::class, 'rejectUser'])->name('reject_user');
        Route::delete('/delete_user,{id}', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('delete_user');
        Route::get('/all_quizzes', [App\Http\Controllers\QuizeController::class, 'allQuizzes'])->name('all_quizzes');
        Route::get('/add_quize', [App\Http\Controllers\QuizeController::class, 'addQuize'])->name('add_quize');
        Route::get('/quize_view{id}', [App\Http\Controllers\QuizeController::class, 'quizeView'])->name('quize_view');
        Route::get('/quize_templet', [App\Http\Controllers\QuizeController::class, 'quizeTemplet'])->name('quize_templet');
        Route::get('/change_quize_info', [App\Http\Controllers\QuizeController::class, 'changeQuizeInfo'])->name('change_quize_info');
        Route::get('/add_question', [App\Http\Controllers\QuizeController::class, 'addQuestion'])->name('add_question');
        Route::get('/add_option', [App\Http\Controllers\QuizeController::class, 'addOption'])->name('add_option');
        Route::get('/change_question_info', [App\Http\Controllers\QuizeController::class, 'changeQuestionInfo'])->name('change_question_info');
        Route::get('/change_option_info', [App\Http\Controllers\QuizeController::class, 'changeOptionInfo'])->name('change_option_info');
        Route::get('/remove_question', [App\Http\Controllers\QuizeController::class, 'removeQuestion'])->name('remove_question');
        Route::get('/remove_option', [App\Http\Controllers\QuizeController::class, 'removeOption'])->name('remove_option');
        Route::get('/set_correct_option', [App\Http\Controllers\QuizeController::class, 'setCorrectOption'])->name('set_correct_option');
        Route::get('/assign_quize{id}', [App\Http\Controllers\QuizeController::class, 'assignQuize'])->name('assign_quize');
        Route::get('/get_candidates', [App\Http\Controllers\QuizeController::class, 'getCandidates'])->name('get_candidates');
        Route::get('/set_user_permission', [App\Http\Controllers\QuizeController::class, 'setUserPermission'])->name('set_user_permission');
        Route::get('/assign_single_candidate', [App\Http\Controllers\QuizeController::class, 'assignSingleCandidate'])->name('assign_single_candidate');
        Route::get('/set_quize', [App\Http\Controllers\QuizeController::class, 'setQuize'])->name('set_quize');
        Route::get('/view_quize_result{id}', [App\Http\Controllers\QuizeController::class, 'viewQuizeResult'])->name('view_quize_result');
        Route::get('/quize_history_view_for_admin/quizeId/{quizeId}/candidateId/{candidateId}', [App\Http\Controllers\QuizeController::class, 'quizeHistoryView'])->name('quize_history_view_for_admin');





    });

    Route::middleware(['IsUser'])->group(function () {
        Route::get('/view_quize,{id}', [App\Http\Controllers\QuizeController::class, 'viewQuize'])->name('view_quize');
        Route::get('/set_answer', [App\Http\Controllers\QuizeController::class, 'setAnswer'])->name('set_answer');
        Route::get('/finish_quize,{id}', [App\Http\Controllers\QuizeController::class, 'finishQuize'])->name('finish_quize');
        Route::get('/submit_quize,{id}', [App\Http\Controllers\QuizeController::class, 'submitQuize'])->name('submit_quize');
        Route::get('/quize_history', [App\Http\Controllers\QuizeController::class, 'quizeHistory'])->name('quize_history');
        Route::get('/quize_history_view/quizeId/{quizeId}/candidateId/{candidateId}', [App\Http\Controllers\QuizeController::class, 'quizeHistoryView'])->name('quize_history_view');

    });
});

