<?php

use App\Http\Controllers\MajorClassController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/login", [UserController::class, 'login']);
Route::get("/login", function () {
    return response()->json(["message" => "Unauthorized", "redirect_to" => "/login"], Response::HTTP_UNAUTHORIZED);
})->name("login");

Route::post("/student/all", [StudentController::class, 'index']);
Route::get("/student/{id}", [StudentController::class, 'show']);
Route::post("/student/search", [StudentController::class, "search"]);
Route::get("/course/{id}", [CourseController::class, "show"]);
Route::post("/course/view", [CourseController::class, "view"]);
Route::get("/major/{id}", [MajorController::class, "show"]);
Route::get("/major-class/{id}", [MajorClassController::class, "show"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::get("/account", function (Request $request) {
        return $request->user();
    });

    Route::put("/student/{id}", [StudentController::class, 'update']);
    Route::delete("/student/{id}", [StudentController::class, 'destroy']);
    Route::post("/student", [StudentController::class, 'store']);
    Route::put("/student/change-image", [StudentController::class, "change_image"]);

    Route::post("/teacher/all", [TeacherController::class, "index"]);
    Route::get("/teacher/{id}", [TeacherController::class, "show"]);
    Route::put("/teacher", [TeacherController::class, "update"]);
    Route::delete("/teacher", [TeacherController::class, "destroy"]);
    Route::post("/teacher", [TeacherController::class, "store"]);
    Route::post("/teacher/search", [TeacherController::class, "search"]);
    Route::put("/teacher/change-image", [TeacherController::class, "change_image"]);

    Route::post("/major/all", [MajorController::class, "index"]);
    Route::put("/major", [MajorController::class, "update"]);
    Route::delete("/major", [MajorController::class, "destroy"]);
    Route::post("/major", [MajorController::class, "store"]);
    Route::post("/major/search", [MajorController::class, "search"]);
    Route::put("/major/change-image", [MajorController::class, "change_image"]);

    Route::post("/major-class/all", [MajorClassController::class, "index"]);
    Route::put("/major-class", [MajorClassController::class, "update"]);
    Route::delete("/major-class", [MajorClassController::class, "destroy"]);
    Route::post("/major-class", [MajorClassController::class, "store"]);
    Route::post("/major-class/search", [MajorClassController::class, "search"]);
    Route::put("/major-class/change-image", [MajorClassController::class, "change_image"]);

    Route::get("/course", [CourseController::class, "index"]);
    Route::put("/course", [CourseController::class, "update"]);
    Route::delete("/course", [CourseController::class, "destroy"]);
    Route::post("/course", [CourseController::class, "store"]);
    Route::post("/course/search", [CourseController::class, "search"]);
});
