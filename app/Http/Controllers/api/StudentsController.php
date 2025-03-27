<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
    * @OA\Get(
    *     path="/students",
    *     tags={"STUDENTS"},
    *     summary="Get all students",
    *     description="Get list of all students.",
    *     @OA\Response(response=200, description="Students retrieved successfully")
    * )
    */
    public function index()
    {
        return Student::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
    * @OA\Post(
    *     path="/students",
    *     tags={"STUDENTS"},
    *     summary="Create a new student",
    *     description="Create a new student with the provided data.",
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"FirstName","LastName","School"},
    *             @OA\Property(property="FirstName", type="string", example="John"),
    *             @OA\Property(property="LastName", type="string", example="Doe"),
    *             @OA\Property(property="School", type="string", example="BCIT")
    *         )
    *     ),
    *     @OA\Response(response=201, description="Student created successfully"),
    *     @OA\Response(response=422, description="Validation error")
    * )
    */
    public function store(Request $request)
    {
        // validate input
        request()->validate([
            'FirstName' => 'required',
            'LastName' => 'required',
            'School' => 'required',
        ]);

        $student = Student::create([
            'FirstName' => request('FirstName'),
            'LastName' => request('LastName'),
            'School' => request('School'),
        ]);
        
        return response()->json($student, 201);
    }

    /**
     * Display the specified resource.
     */
    /**
    * @OA\Get(
    *     path="/students/{id}",
    *     tags={"STUDENTS"},
    *     summary="Get a specific student",
    *     description="Get details of a specific student by ID.",
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="ID of the student",
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(response=200, description="Student details retrieved successfully"),
    *     @OA\Response(response=404, description="Student not found")
    * )
    */
    public function show($id)
    {
        $student = Student::find($id);
        
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
    * @OA\Put(
    *     path="/students/{id}",
    *     tags={"STUDENTS"},
    *     summary="Update a student",
    *     description="Update details of a specific student by ID.",
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="ID of the student",
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"FirstName","LastName","School"},
    *             @OA\Property(property="FirstName", type="string", example="Jane"),
    *             @OA\Property(property="LastName", type="string", example="Smith"),
    *             @OA\Property(property="School", type="string", example="UBC")
    *         )
    *     ),
    *     @OA\Response(response=200, description="Student updated successfully"),
    *     @OA\Response(response=404, description="Student not found"),
    *     @OA\Response(response=422, description="Validation error")
    * )
    */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        
        // validate input
        request()->validate([
            'FirstName' => 'required',
            'LastName' => 'required',
            'School' => 'required',
        ]);

        $isSuccess = $student->update([
            'FirstName' => request('FirstName'),
            'LastName' => request('LastName'),
            'School' => request('School'),
        ]);

        return response()->json([
            'success' => $isSuccess,
            'data' => $student
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
    * @OA\Delete(
    *     path="/students/{id}",
    *     tags={"STUDENTS"},
    *     summary="Delete a student",
    *     description="Delete a specific student by ID.",
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="ID of the student to delete",
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(response=200, description="Student deleted successfully"),
    *     @OA\Response(response=404, description="Student not found")
    * )
    */
    public function destroy($id)
    {
        $student = Student::find($id);
        
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        
        $isSuccess = $student->delete();
    
        return response()->json([
            'success' => $isSuccess
        ]);    
    }
}
