<?php

use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnifiedController;
use Illuminate\Support\Facades\File;
Route::get('/', function () {
    return view('welcome');
})->name("home");
Route::get('/singup', function() {
return view("singup");
})-> name('singup');
Route::post('/register', [HomeController::class, 'register'])->name('register.submit');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// CSRF Token Refresh Route
Route::middleware('web')->get('/refresh-csrf', function () {
    return response()->json(['token' => csrf_token()]);
});

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/roler', [AuthController::class, 'roler'])->name('roler');

    Route::middleware('auth')->get('/private/{filename}', function ($filename) {
        $path = storage_path('app/private/' . $filename);

        if (!Storage::disk('private')->exists($filename)) {

            abort(404);
        }

        return Storage::disk('private')->response($filename);
    })->where('filename', '.*')->name('img');



// New Unified System Routes
Route::middleware(['auth'])->prefix('unified')->name('unified.')->group(function () {
    // My Requests - All Roles
    Route::get('/myrequests', [UnifiedController::class, 'myrequests'])->name('myrequests');
    Route::get('/myrequests-data', [UnifiedController::class, 'getMyRequestsData'])->name('myrequestsData');
    Route::post('/storecard', [UnifiedController::class ,'storecard'])->name('storecard');
    // Add/Edit Requests - All Roles
    Route::get('/addoreditrequests/{id?}', [UnifiedController::class, 'addoreditrequests'])->name('addoreditrequests');

    Route::post('/storerequest', [UnifiedController::class, 'storerequest'])->name('storerequest');
    Route::put('/updaterequest/{id}', [UnifiedController::class, 'updaterequest'])->name('updaterequest');
    Route::delete('/deleterequest/{id}', [UnifiedController::class, 'deleterequest'])->name('deleterequest');

    // All Requests - Admin/Master Only
    Route::get('/allrequests', [UnifiedController::class, 'allrequests'])->name('allrequests');

    // Request Detail - Admin/Master Only
    Route::post('/requestdetail/{id}', [UnifiedController::class, 'requestdetail'])->name('requestdetail');
    Route::post('/update-request-field', [UnifiedController::class, 'updateRequestField'])->name('updateRequestField');
    Route::post('/upload-file', [UnifiedController::class, 'uploadFile'])->name('uploadFile');
    Route::post('/upload-pdf', [UnifiedController::class, 'uploadpdf'])->name('uploadpdf');
    Route::get('/get-request-data/{id}', [UnifiedController::class, 'getRequestData'])->name('getRequestData');
    Route::post('/accept', [UnifiedController::class, 'accept'])->name('accept');
    Route::post('/reject', [UnifiedController::class, 'reject'])->name('reject');
    Route::post('/epointment', [UnifiedController::class, 'epointment'])->name('epointment');

    // Messages - All Roles
    Route::get('/message/{id?}', [UnifiedController::class, 'message'])->name('message');
    Route::post('/storemessage/{id}', [UnifiedController::class, 'storemessage'])->name('storemessage');

    // Accepted Requests - Admin/Master Only
    Route::get('/acceptes', [UnifiedController::class, 'acceptes'])->name('acceptes');

    // Users Management - Admin/Master Only

    Route::get('/users', [UnifiedController::class, 'users'])->name('users');
    Route::get('/userdetail/{id}', [UnifiedController::class, 'userdetail'])->name('userdetail');
    Route::delete('/deleteuser/{id}', [UnifiedController::class, 'deleteuser'])->name('deleteuser');

    // Master Only
    Route::get('/admin/{id}', [UnifiedController::class, 'admin'])->name('admin');
    Route::get('/nadmin/{id}', [UnifiedController::class, 'nadmin'])->name('nadmin');

    // Add Profile - Admin Only
    Route::get('/addprofile', [UnifiedController::class, 'addprofile'])->name('addprofile');
    Route::post('/storeprofile', [UnifiedController::class, 'storeprofile'])->name('storeprofile');
    Route::get('/editprofile/{id}', [UnifiedController::class, 'editprofile'])->name('editprofile');
    Route::put('/updateprofile/{id}', [UnifiedController::class, 'updateprofile'])->name('updateprofile');
    //tests
    Route::get('/requestform/{id?}', [UnifiedController::class, 'requestform'])->name('requestform');
    Route::get('/editrequest/{id?}', [UnifiedController::class, 'editrequest'])->name('editrequest');
    Route::post('/storerequestform', [UnifiedController::class, 'storerequestform'])->name('storerequestform');






    Route::get('/cancel/{id?}', [UnifiedController::class, 'cancel'])->name('cancel');
});






