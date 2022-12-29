<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// Route::prefix('v1')->group(function () {
//     Route::get('/products', function () {
//         $product = new ProductsController();
//         return $product->index();
//     });
//     Route::post('/products', [ProductsController::class, 'store']);
// });

// Route::controller(ProductsController::class)->prefix('v1')->group(function () {
//     Route::get('/products', 'index')->name('products');
//     Route::post('/products', 'store')->name('products-store');
//     Route::put('/products/{product}', 'update')->name('products-update');
//     Route::get('/products/{product}', 'show')->name('products-show');
//     Route::delete('/products/{product}', 'destroy')->name('products-delete');
// });

// Broadcast::middleware('auth:sanctum')->routes();

// Auth::routes();

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', function () {
        return User::all();
    });
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::get('list_tokens', function (Request $request) {
        return $request->user()->tokens;
    });

    Route::get('token_abilities', function (Request $request) {
        $abilities = [];

        if ($request->user()->tokenCan('system:update')) {
            array_push($abilities, ...['posso atualizar']);
        }
        if ($request->user()->tokenCan('system:create')) {
            array_push($abilities, ...['posso criar']);
        }
        if ($request->user()->tokenCan('system:all')) {
            array_push($abilities, ...['posso tudo']);
        }
        return $abilities;
    });

    Route::delete('revoke_all_tokens', function (Request $request) {
        $request->user()->tokens()->delete();
        return response()->json([], 204);
    });

    Route::delete('revoke_current_token', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json([], 204);
    });

    Route::delete('revoke_specific_token', function (Request $request) {
        // $id = $request->query('id');
        $id_body = $request->json('id');
        $request->user()->tokens()->where('id', $id_body)->delete();
        return response()->json([], 204);
    });
});

Route::post('/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        // 'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'credentials error' => ['The provided credentials are incorrect.'],
        ]);
    }
    $token_can_all = $user->createToken('can_all')->plainTextToken;
    $token_can_update = $user->createToken('can_update', ['system:update'])->plainTextToken;
    $token_can_create = $user->createToken('can_create', ['system:create'])->plainTextToken;

    $abilities = [$token_can_all, $token_can_create, $token_can_update];
    return $abilities;
});

// Route::group(function () {
//     Route::resources([
//         'products' => ProductsController::class,
//         'users' => UsersController::class
//     ]);
// });