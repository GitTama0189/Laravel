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

use App\Task;
use Illuminate\Http\Request;

Route::get('/', function () {
    $tasks = Task::orderBy('created_at', 'asc')->get();
    $users = Task::orderBy('id', 'asc')->get();

    return view('tasks', [
        'tasks' => $tasks,
        'users' => $users
        
    ]);
});


route::delete('/task/{id}', function ($id) {
    if (Auth::check()) {
        Task::findOrFail($id)->delete();

        return redirect('/');
    }
    return redirect('/home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/task', function (Request $request) {
    if (Auth::check()) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:191',
    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    $task = new Task;
    $task->name = $request->name;
    $task->user_id = Auth::id();
    $task->save();

    return redirect('/');
    }
    return redirect('/home');

});
