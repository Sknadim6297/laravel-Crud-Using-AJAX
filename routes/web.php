<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('add-student', function () {
    return view('form');
});
Route::post('add-student', [StudentController::class, 'create'])->name('add-student');

Route::get('students', function () {
    return view('students');
});
Route::get('get-students', [StudentController::class, 'getStudents'])->name('get-students');
Route::get('students/edit/{id}', [StudentController::class, 'editStudent'])->name('edit-student');


Route::delete('/students/delete/{id}', [StudentController::class, 'deleteStudent'])->name('delete-student');
Route::post('/update-data/{id}', [StudentController::class, 'updateData'])->name('update-student');

Route::get('/search', [StudentController::class, 'search']);
