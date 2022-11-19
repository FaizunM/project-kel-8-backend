<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "username" => "required",
                "password" => "required"
            ]);

            if ($validator->fails()) {
                return response()->json(["message" => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
            }

            $user = User::all()->where('username', $request->username)->where("password", $request->password);

            if (count($user) > 0) {
                $token = $user->first()->createToken("Bearer");

                return response()->json(["data" => ["status" => 1, "message" => "Login successfully", "token" => $token->plainTextToken, "token_type" =>
                "Bearer"]], 200);
            }

            $student = Student::all()->where('nisn', $request->username)->where("date_of_birth", $request->password);

            if (count($student) > 0) {
                $token = $student->first()->createToken("Bearer");

                return response()->json(["data" => ["status" => 1, "message" => "Login successfully", "token" => $token->plainTextToken, "token_type" =>
                "Bearer"]], 200);
            }

            $teacher = Student::all()->where('nuptk', $request->username)->where("date_of_birth", $request->password);

            if (count($teacher) > 0) {
                $token = $teacher->first()->createToken("Bearer");

                return response()->json(["data" => ["status" => 1, "message" => "Login successfully", "token" => $token->plainTextToken, "token_type" =>
                "Bearer"]], 200);
            }

            return response()->json(["status" => 0, "message" => "Login failed, username or password invalid"], 400);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function change_image(Request $request, $id)
    {
        try {
            if ($request->user()->role == "admin" || $request->user()->role == "student") {
                $validator = Validator::make($request->all(), [
                    "image" => "file|image"
                ]);

                if ($validator->fails()) {
                    return response()->json(["message" => $validator->errors()], Response::HTTP_BAD_REQUEST);
                }

                $img = null;
                $img_file = $request->file("image");

                if ($request->file("image")) {
                    $img = Storage::disk("public")->put("/", $img_file);
                }

                $student = User::findOrFail($id)->update(['image' => $img]);

                return response()->json(["message" => "Image updated successfully"], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
