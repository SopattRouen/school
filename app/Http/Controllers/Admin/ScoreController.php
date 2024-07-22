<?php

namespace App\Http\Controllers\Admin;
use App\Models\Score\Score;
use App\Models\User\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScoreController
{
    //
    
    public function store(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate(
            [
                'type_id' => 'required|exists:user,id', // Assuming 'users' is the table name
                'subject_id' => 'required|exists:subject,id', // Assuming 'subjects' is the table name
                'score' => 'required|min:0|max:150',
            ], 
            [
                'type_id.required' => 'សូមបញ្ចូលឈ្មោះសិស្ស',
                'type_id.exists' => 'សូមបញ្ជូលឈ្មោះសិស្សមិនមានក្នុងបញ្ជី',
                'subject_id.required' => 'សូមជ្រើសរើសឈ្មោះមុខវិជ្ជា',
                'subject_id.exists' => 'សូមបញ្ជូលឈ្មោះមុខវិជ្ជាមិនមានក្នុងបញ្ជី',
                'score.required' => 'សូមបញ្ចូលពិន្ទុ',
                'score.min' => 'ពិន្ទុតម្លៃអប្បបរមា 0',
                'score.max' => 'ពិន្ទុតម្លៃអតិបរមា 150',
            ],
        );
    
        // Find the user by type_id
        $user = UserModel::find($validated['type_id']);
    
        // Check if the user exists and is of type_id = 3 (assuming this means student)
        if (!$user || $user->type_id != 3) {
            return response()->json(['message' => 'ឈ្មោះសិស្សគ្មានក្នុងបញ្ជី'], 400);
        }
    
        // Create a new Score record
        $score = new Score();
        $score->type_id = $validated['type_id'];
        $score->subject_id = $validated['subject_id'];
        $score->score = $validated['score'];
        $score->save();
    
        // Return a JSON response with success message and data
        return response()->json([
            'message' => 'ការបញ្ចូលពិន្ទុរបានជោគជ័យ',
            'data' => [
                'student name' => $user->name,
                'subject name' => $score->subject->name, // Assuming Score belongsTo Subject relationship
                'score' => $score->score,
            ]
        ], 200);
    }
    public function view($type_id)
    {
        // Retrieve all scores related to the student (type_id)
        $scores = Score::where('type_id', $type_id)
                        ->with(['user', 'subject']) // Eager load the user and subject relationships
                        ->get();

        if ($scores->isEmpty()) {
            return response()->json([
                'message' => 'បរាជ័យ',  // Failure message in Khmer
                'status' => 'រកមិនឃើញទិន្នន័យ',  // Data not found message in Khmer
            ], 404);  // It's better to use 404 for not found resources
        }

        // Prepare the response data array
        $responseData = [];
        foreach ($scores as $score) {
            $responseData[] = [
                'score_id' => $score->id,
                'student_name' => $score->user->name,  // Assuming the User model has a 'name' attribute
                'subject_name' => $score->subject->name,  // Assuming the Subject model has a 'name' attribute
                'score' => $score->score
            ];
        }

        return response()->json([
            'message' => 'ជោគជ័យ',  // Success message in Khmer
            'data' => $responseData
        ], 200);
    }

    public function getAll()
    {
        $responseData = [];
        $data = Score::with('user', 'subject')->get(); // Eager load user and subject relationships
        
        // Define the mapping of type_id to type names
        $typeMapping = [
            1 => 'admin',
            2 => 'staff',
            3 => 'student',
            // Add other mappings as necessary
        ];
        
        foreach ($data as $score) {
            $userTypeId = $score->user->type_id;  // Assuming 'type_id' is a direct attribute of the User model
            $typeName = isset($typeMapping[$userTypeId]) ? $typeMapping[$userTypeId] : 'unknown'; // Default to 'unknown' if type_id is not in the mapping array
            
            $responseData[] = [
                'type_id' => $typeName,
                'score_id' => $score->id,
                'student_name' => $score->user->name,  // Assuming the User model has a 'name' attribute
                'subject_name' => $score->subject->name,  // Assuming the Subject model has a 'name' attribute
                'score' => $score->score
            ];
        }
        
        return response()->json([
            'message' => 'ជោគជ័យ',
            'data' => $responseData,
        ]);
    }

    // public function searchScores(Request $request)
    // {
    //     $searchQuery = $request->input('name');
        
    //     // Define the mapping of type_id to type names
    //     $typeMapping = [
    //         1 => 'admin',
    //         2 => 'staff',
    //         3 => 'student',
    //         // Add other mappings as necessary
    //     ];

    //     // Retrieve scores based on the search query
    //     $data = Score::whereHas('user', function ($query) use ($searchQuery) {
    //         $query->where('name', 'like', '%' . $searchQuery . '%');
    //     })->with('user', 'subject')->get();

    //     $responseData = [];
        
    //     foreach ($data as $score) {
    //         $userTypeId = $score->user->type_id;  // Assuming 'type_id' is a direct attribute of the User model
    //         $typeName = isset($typeMapping[$userTypeId]) ? $typeMapping[$userTypeId] : 'unknown'; // Default to 'unknown' if type_id is not in the mapping array
            
    //         $responseData[] = [
    //             'type_name' => $typeName,
    //             'score_id' => $score->id,
    //             'student_name' => $score->user->name,  // Assuming the User model has a 'name' attribute
    //             'subject_name' => $score->subject->name,  // Assuming the Subject model has a 'name' attribute
    //             'score' => $score->score
    //         ];
    //     }
        
    //     return response()->json([
    //         'message' => 'ជោគជ័យ',
    //         'data' => $responseData,
    //     ]);
    // }

    public function update(Request $req)
    {
        // Validate the request data
        $validatedData = $req->validate([
            'student_name' => 'required|string',
            'subject_id' => 'required|integer',
            'score' => 'required|max:150|min:0',
        ]);
    
        // Find the user by their name
        $user = UserModel::where('name', $validatedData['student_name'])->first();
    
        if (!$user) {
            // Return a failure response if the user is not found
            return response()->json([
                'status' => 'បរាជ័យ',
                'message' => 'សិស្សដែលផ្តល់ឲ្យមិនត្រូវទេ',
            ], 422);
        }
    
        // Find the score record associated with the user and subject
        $score = Score::where('type_id', $user->id)
                      ->where('subject_id', $validatedData['subject_id'])
                      ->first();
    
        if ($score) { // If the score record exists
            // Update the score record with the new data
            $score->score = $validatedData['score'];
    
            // Save the updated score record to the database
            $score->save();
    
            // Return a success response
            return response()->json([
                'status' => 'ជោគជ័យ',
                'message' => 'ទិន្នន័យត្រូវបានកែប្រែ',
                'data' => $score,
            ], 200);
        } else { // If the score record does not exist
            // Return a failure response
            return response()->json([
                'status' => 'បរាជ័យ',
                'message' => 'ទិន្នន័យដែលផ្តល់ឲ្យមិនត្រូវទេ',
            ], 422);
        }
    }
    public function delete($student_id)
    {
        // Find the student by their ID
        $student = UserModel::find($student_id);

        // Check if the student exists and is of type student
        if (!$student || $student->type_id != 3) {
            // Return failure response if student is not found or not of type student
            return response()->json([
                'status' => 'បរាជ័យ',
                'message' => 'មិនអាចរកឃើញសិស្សដែលត្រូវបានលុប',
            ], 404);
        }

        // Retrieve all scores associated with the student
        $scores = Score::where('type_id', $student_id)->get();

        // Delete all scores associated with the student
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Delete all scores associated with the student
            foreach ($scores as $score) {
                $score->delete();
            }

            // Commit the transaction
            DB::commit();

            // Success response back to client
            return response()->json([
                'status' => 'ជោគជ័យ',
                'message' => 'លុបទិន្នន័យទទួលបានជោគជ័យ',
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollback();

            // Failure response back to client
            return response()->json([
                'status' => 'បរាជ័យ',
                'message' => 'មានបញ្ហា​ខុសគ្នា: ' . $e->getMessage(),
            ], 500);
        }
    }
}
