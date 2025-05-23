<?php
// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Infrastructure\Http\Controllers\Api\AuthController; 
use App\Infrastructure\Http\Controllers\Api\PixKeyController;
use App\Infrastructure\Http\Controllers\Api\PixTransactionController;
use App\Infrastructure\Http\Controllers\Api\AccountController;

Route::post('auth/login', [AuthController::class, 'login']);


Route::prefix('pix/keys')->group(function () {
    Route::post('/', [PixKeyController::class, 'create']);
    Route::get('/{id}', [PixKeyController::class, 'findById']);
});


Route::prefix('pix/transactions')->group(function () {
    Route::post('/', [PixTransactionController::class, 'create']);
    Route::get('/{id}', [PixTransactionController::class, 'findById']);
});


Route::post('account/balance', [AccountController::class, 'getBalance']);

?>
