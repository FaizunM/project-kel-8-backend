<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorecourceRequest;
use App\Http\Requests\UpdatecourceRequest;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $course = Course::all();

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
                    "student_id" => "required|integer",
                    "name" => "required|string",
                    "score" => "required|string",
                ]);

                if ($validator->fails()) {
                    return response()->json(["message" => $validator->errors()], Response::HTTP_BAD_REQUEST);
                }

                $course = new Course;
                $course->student_id = $request->student_id;
                $course->name = $request->name;
                $course->score = $request->score;
                $course->save();

                return response()->json(["message" => "Course added successfully"], 200);
            }

            return response()->json(["message" => "Access denied, only admin and teacher can access this route"], Response::HTTP_UNAUTHORIZED);
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
            $course = Course::findOrFail($id);

            return response()->json(["data" => $course], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function view(Request $request)
    {
        try {
            $course = Course::all()->where("student_id", $request->student_id)->first();

            return response()->json(["data" => $course], 200);
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
                    "student_id" => "required|integer",
                    "name" => "required|string",
                    "score" => "required|string",
                ]);

                if ($validator->fails()) {
                    return response()->json(["message" => $validator->errors()], Response::HTTP_BAD_REQUEST);
                }

                $course = Course::findOrFail($id)->update($request->all());

                return response()->json(["message" => "Course updated successfully"], 200);
            }

            return response()->json(["message" => "Access denied, only admin dan teacher can access this route"], Response::HTTP_UNAUTHORIZED);
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
                $course = Course::findOrFail($id)->delete();

                return response()->json(["message" => "Course deleted successfully"], 200);
            }

            return response()->json(["message" => "Access denied, only admin and teacher can access this route"], Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function search(Request $request)
    {
        try {
            $search = Course::where("name", "like", "%" . $request->name . "%")->paginate($request->per_page);

            return response()->json($search, 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
