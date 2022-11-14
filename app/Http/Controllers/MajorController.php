<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMajorRequest;
use App\Http\Requests\UpdateMajorRequest;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $major = Major::all();

            return response()->json(["data" => $major], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($request->user()->role == "admin") {
                $validator = Validator::make($request->all(), [
                    "name" => "required|string",
                ]);

                if ($validator->fails()) {
                    return response()->json(["message" => $validator->errors()], Response::HTTP_BAD_REQUEST);
                }

                $major = new Major;
                $major->name = $request->name;
                $major->save();

                return response()->json(["message" => "Major added successfully"], 200);
            }

            return response()->json(["message" => "Access denied, only admin can access this route"], Response::HTTP_UNAUTHORIZED);
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

                $student = Major::findOrFail($id)->update(['image' => $img]);

                return response()->json(["message" => "Image updated successfully"], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $major = Major::findOrFail($id);

            return response()->json(["data" => $major], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if ($request->user()->role == "admin") {
                $validator = Validator::make($request->all(), [

                    "name" => "string",
                ]);

                if ($validator->fails()) {
                    return response()->json(["message" => $validator->errors()], Response::HTTP_BAD_REQUEST);
                }

                $major = Major::findOrFail($id)->update($request->all());

                return response()->json(["message" => "Major updated successfully"], 200);
            }

            return response()->json(["message" => "Access denied, only admin can access this route"], Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            if ($request->user()->role == "admin") {
                $major = Major::findOrFail($id)->delete();

                return response()->json(["message" => "Major deleted successfully"], 200);
            }

            return response()->json(["message" => "Access denied, only admin can access this route"], Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function search(Request $request)
    {
        try {
            $search = Major::where("fullname", "like", "%" . $request->name . "%")->paginate($request->per_page);

            return response()->json($search, 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
