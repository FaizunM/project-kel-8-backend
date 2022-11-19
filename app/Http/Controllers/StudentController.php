<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;;

use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $student = Student::orderBy("updated_at", "desc")->paginate($request->per_page);

            return response()->json(["data" => $student], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function course_grade($id)
    {
        try {
            $course = Course::all()->where('student_id', $id);

            return response()->json(["data" => $course], 200);
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
            if ($request->user()->role == "admin" || $request->user()->role == "teacher") {
                $validator = Validator::make($request->all(), [
                    "nis" => "required|integer",
                    "nisn" => "required|integer",
                    "fullname" => "required|string",
                    "date_of_birth" => "required|date",
                    "place_of_birth" => "required|string",
                    "gender" => "required|string",
                    "religion" => "required|string",
                    "address" => "required|string",
                    "major_class_id" => "required|integer",
                    "major_id" => "required|integer",
                    "email" => "required|string",
                    "phone_number" => "required|string",
                ]);

                if ($validator->fails()) {
                    return response()->json(["message" => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
                }

                $student = new Student;
                $student->nis = $request->nis;
                $student->nisn = $request->nisn;
                $student->fullname = $request->fullname;
                $student->date_of_birth = $request->date_of_birth;
                $student->place_of_birth = $request->place_of_birth;
                $student->gender = $request->gender;
                $student->religion = $request->religion;
                $student->address = $request->address;
                $student->major_class_id = $request->major_class_id;
                $student->major_id = $request->major_id;
                $student->email = $request->email;
                $student->phone_number = $request->phone_number;
                $student->save();

                return response()->json(["message" => "Student added successfully", "data" => $student], 200);
            }

            return response()->json(["message" => "Access denied, only admin and teacher can access this route"], Response::HTTP_UNAUTHORIZED);
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

                $student = Student::findOrFail($id)->update(['image' => $img]);

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
            $student = Student::findOrFail($id);

            return response()->json(["data" => $student], 200);
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
            if ($request->user()->role == "admin" || $request->user()->role == "teacher") {
                $validator = Validator::make($request->all(), [
                    "nis" => "integer",
                    "nisn" => "integer",
                    "fullname" => "string",
                    "date_of_birth" => "date",
                    "place_of_birth" => "string",
                    "gender" => "string",
                    "religion" => "string",
                    "address" => "string",
                    "major_class_id" => "integer",
                    "major_id" => "integer",
                    "email" => "string",
                    "phone_number" => "string",
                ]);

                if ($validator->fails()) {
                    return response()->json(["message" => $validator->errors()], Response::HTTP_BAD_REQUEST);
                }

                $student = Student::findOrFail($id)->update($request->all());

                return response()->json(["message" => "Student updated successfully"], 200);
            }

            return response()->json(["message" => "Access denied, only admin and teacher can access this route"], Response::HTTP_UNAUTHORIZED);
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
            if ($request->user()->role == "admin" || $request->user()->role == "teacher") {
                $student = Student::findOrFail($id)->delete();

                return response()->json(["message" => "Student deleted successfully"], 200);
            }

            return response()->json(["message" => "Access denied, only admin and teacher can access this route"], Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function search(Request $request)
    {
        try {
            $search = Student::where("fullname", "like", "%" . $request->name . "%")->paginate($request->per_page);

            return response()->json($search, 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
