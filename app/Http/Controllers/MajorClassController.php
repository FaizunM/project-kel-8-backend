<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMajorClassRequest;
use App\Http\Requests\UpdateMajorClassRequest;
use App\Models\MajorClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MajorClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $major_class = MajorClass::paginate($request->per_page);;

            return response()->json(["data" => $major_class], 200);
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

                $major_class = new MajorClass;
                $major_class->name = $request->name;
                $major_class->save();

                return response()->json(["message" => "Major Class added successfully"], 200);
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

                $student = MajorClass::findOrFail($id)->update(['image' => $img]);

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
            $major_class = MajorClass::findOrFail($id);

            return response()->json(["data" => $major_class], 200);
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

                $major_class = MajorClass::findOrFail($id)->update($request->all());

                return response()->json(["message" => "Major Class updated successfully"], 200);
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
                $major_class = MajorClass::findOrFail($id)->delete();

                return response()->json(["message" => "Major Class   deleted successfully"], 200);
            }

            return response()->json(["message" => "Access denied, only admin can access this route"], Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function search(Request $request)
    {
        try {
            $search = MajorClass::where("name", "like", "%" . $request->name . "%")->paginate($request->per_page);

            return response()->json($search, 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
