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
    $tasks = Task::orderBy('tasks.created_at', 'asc')
                ->select('tasks.id as task_id','users.id', 'tasks.name as task_name', 'users.name',
                         'tasks.created_at as task_created_at', 'tasks.updated_at as task_updated_at')
                ->leftJoin('users', 'tasks.user_id', '=', 'users.id')
                ->get();

    return view('tasks', [
        'tasks' => $tasks,
        
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
Route::put('/task/{id}', function ($id, Request $request) {
    if (Auth::check()) {
        DB::table('tasks')
            ->where('id', $id)
            ->update(['user_id' => Auth::id(), 'name' => $request->name]);

        return redirect('/');
    }

   return redirect('/home');

});
