<?php
// routes/api.php
use Illuminate\Support\Facades\Route;
use Infrastructure\Http\Controllers\Api\AuthController;


Route::post('auth/login', [AuthController::class, 'login']);

?>