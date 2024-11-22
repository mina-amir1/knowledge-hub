<?php

use App\Http\Controllers\AttachmentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MeetingsController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('login', [AuthController::class,'showLoginForm']);
Route::post('login',[AuthController::class,'login'])->name('login');
Route::get('logout',[AuthController::class,'logout'])->name('logout');
Route::get('activate/{token}',[AuthController::class,'activationShow'])->name('activate.show');
Route::post('activate/{token}',[AuthController::class,'activateUser'])->name('activate');

Route::middleware(['auth','role:admin'])->group(function (){
    Route::get('admin',function (){
        return view('layout');
    })->name('admin');
    Route::get('users',[UserController::class,'index'])->name('users.index');
    Route::get('user/create',[UserController::class,'createUser'])->name('users.create');
    Route::post('user/create',[UserController::class,'store'])->name('users.store');
    Route::get('user/{id}/block',[UserController::class,'block'])->name('users.block');
    Route::get('user/{id}/unblock',[UserController::class,'unblock'])->name('users.unblock');
    Route::get('user/{id}/invite',[UserController::class,'reinvite'])->name('users.invite');

    Route::get('attachments',[AttachmentsController::class,'index'])->name('attachments.index');
    Route::get('attachments/{id}/approve',[AttachmentsController::class,'approve'])->name('attachments.approve');
    Route::get('attachments/{id}/block',[AttachmentsController::class,'block'])->name('attachments.block');
    Route::get('attachments/{id}/delete',[AttachmentsController::class,'destroy'])->name('attachments.delete');

});

Route::middleware(['auth','active'])->group(function () {
    Route::get('profile', [UserController::class, 'profileShow'])->name('profile.show');
    Route::post('profile', [UserController::class, 'profileUpdate'])->name('profile.store');

    Route::get('threads', [ThreadController::class, 'index'])->name('threads.index');
    Route::get('threads/create', [ThreadController::class, 'create'])->name('threads.create');
    Route::post('threads/create', [ThreadController::class, 'store'])->name('threads.store');
    Route::get('threads/{id}', [ThreadController::class, 'show'])->name('threads.show');
    Route::get('threads/{id}/edit', [ThreadController::class, 'edit'])->name('threads.edit');
    Route::post('threads/{id}/update', [ThreadController::class, 'update'])->name('threads.update');
    Route::get('threads/{id}/delete', [ThreadController::class, 'destroy'])->name('threads.delete');

    Route::post('threads/{thread_id}/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::get('comment/{id}/delete', [CommentController::class, 'destroy'])->name('comment.destroy');

    Route::get('meetings', [MeetingsController::class, 'index'])->name('meetings.index');
    Route::get('meetings/create', [MeetingsController::class, 'createMeeting'])->name('meetings.create');
    Route::post('meetings/store', [MeetingsController::class, 'store'])->name('meetings.store');
    Route::get('meetings/{id}', [MeetingsController::class, 'show'])->name('meetings.show');
    Route::get('meetings/{id}/edit', [MeetingsController::class, 'edit'])->name('meetings.edit');
    Route::post('meetings/{id}/update', [MeetingsController::class, 'update'])->name('meetings.update');
    Route::get('meetings/{id}/delete', [MeetingsController::class, 'destroy'])->name('meetings.destroy');

    Route::post('/notifications/mark-as-seen', [UserController::class, 'markAsSeen'])->name('notifications.markAsSeen');


    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::get('user/{user_id}/meetings/{id}/calendar', [MeetingsController::class, 'generateCalendar'])->name('meeting.calendar');


