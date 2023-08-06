<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\AboutController;
use \App\Http\Controllers\PostsController;

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

$posts = [
    1 => [
        'title' => 'Intro Laravel',
        'content' => 'This is a short intro to Laravel'
    ],
    2 => [
        'title' => 'Intro to PHP',
        'content' => 'This is a short intro to PHP'
    ]
];

Route::get('/', [HomeController::class, 'home'])->name('home.index');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::get('/single', AboutController::class)->name('home.about');

Route::resource('posts', PostsController::class)->only(['index', 'show']);

/*Route::get('/posts/', function() use ($posts) {
    return view('posts.index', ['posts' => $posts]);
})->name('posts.index');

Route::get('/posts/{id}', function ($id) use ($posts) {
    abort_if(!isset($posts[$id]), 404);

    return view('posts.show', ['post' => $posts[$id]]);
})->name('posts.show');

Route::get('/recent-posts/{days_ago?}', function ($daysAgo = 20) {
    return "Posts from " . $daysAgo . ' days ago';
})->name('posts.recent.index')->middleware('auth');*/

Route::prefix('/fun')->name('fun.')->group(function() use ($posts) {
    Route::get('/response', function () use ($posts) {
        return response($posts, 201)
            ->header('Content-Type', 'application/json')
            ->cookie('MY_COOKIE', 'Piotr Jura', 3600);
    })->name('response');

    Route::get('/json', function () use ($posts) {
        return response()->json($posts);
    })->name('json');

    Route::get('/redirect', function() {
        return redirect('/contact');
    })->name('redirect');

    Route::get('/back', function() {
        return back();
    })->name('back');

    Route::get('/named-route', function() {
        return redirect()->route('posts.show', ['id' => 1]);
    })->name('named-route');

    Route::get('/download', function() {
        return response()->download(public_path('/39 - daniel.jpg'), 'face.jpg');
    })->name('download');
});
