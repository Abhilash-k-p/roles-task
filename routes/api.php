<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->get('/dashboard', function (Request $request) {
    $userRoles = $request->user()->roles->pluck('role_name')->toArray();

    // Retrieve menus based on roles
    $menuConfig = config('menu');
    $menus = [];
    foreach ($userRoles as $role) {
        if (isset($menuConfig[$role])) {
            $menus = array_merge($menus, $menuConfig[$role]);
        }
    }

    return response()->json([
        'message' => 'Welcome to the dashboard!',
        'user' => $request->user(),
        'roles' => $userRoles,
        'menus' => array_unique($menus),
    ]);
});
