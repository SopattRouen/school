<?php

namespace App\Http\Controllers\Admin;

use App\Models\Score\Grade;
use App\Models\Score\Score;
use App\Models\User\UserModel;
class GradeController
{
    //
    public function grade($id = 0)
    {
        // Get the user
        $user = UserModel::findOrFail($id);

        // Get scores for the user
        $scores = Score::where('type_id', $id)->get();
        if (!$user || $user->type_id != 3) {
            return response()->json(['message' => 'ឈ្មោះសិស្សគ្មានក្នុងបញ្ជី'], 400);
        }

        if ($scores->isEmpty()) {
            return response()->json(['message' => 'មិនមានពិន្ទុរសម្រាប់សិស្សម្នាក់នេះ'], 404);
        }

        // Calculate average score
        $totalScore = 0;
        $totalCount = $scores->count();

        foreach ($scores as $score) {
            $totalScore += $score->score;
        }

        $averageScore = $totalCount > 0 ? $totalScore / $totalCount : 0;

        // Assign grade based on average score
        $finalGrade = $this->calculateGrade($averageScore);

        // Save average and grade to grades table
        $grade = new Grade();
        $grade->type_id = $id; // Assuming 'type_id' is the foreign key linking to UserModel
        $grade->score_id = $score->id; // Assuming 'score_id' is the foreign key linking to Score model
        $grade->average = $averageScore;
        $grade->grade = $finalGrade;
        $grade->save();
        $data=[
            'type_id' => $user->type->name,
            'name' => $user->name,
            'average' => $averageScore,
            'total_score'=>$totalScore,
            'grade' => $finalGrade,
            
        ];
        return response()->json([
            'message' => 'ជោគជ័យ',
            'data' => $data,
        ]);
    }

    public function getData()
    {
        // Retrieve all scores for users with type_id = 3 (students)
        $scores = Score::whereHas('user', function($query) {
            $query->where('type_id', 3);
        })->with('user.type')->get();

        $data = [];

        // Group scores by user
        $scoresByUser = $scores->groupBy('type_id');

        // Loop through each user to calculate average score and assign grade
        foreach ($scoresByUser as $typeId => $userScores) {
            $user = $userScores->first()->user;
            $totalScore = 0;
            $totalCount = $userScores->count();

            // Calculate the total score
            foreach ($userScores as $score) {
                $totalScore += $score->score;
            }

            // Calculate the average score
            $averageScore = $totalCount > 0 ? $totalScore / $totalCount : 0;

            // Assign grade based on average score
            $finalGrade = $this->calculateGrade($averageScore);

            // Save average and grade to grades table
            foreach ($userScores as $score) {
                Grade::updateOrCreate(
                    ['score_id' => $score->id], // Assuming 'score_id' is the foreign key linking to Score model
                    [
                        'average' => $averageScore,
                        'grade' => $finalGrade,
                        'type_id' => $user->type->id
                    ]
                );
            }

            // Collect user data along with calculated average and grade
            $data[] = [
                'type_id' => $user->type->name,
                'name' => $user->name,
                'average' => $averageScore,
                'grade' => $finalGrade
            ];
        }

        return response()->json([
            'message' => 'ជោគជ័យ',
            'data' => $data,
        ]);
    }


    private function calculateGrade($averageScore)
    {
        if ($averageScore >= 90) {
            return 'A';
        } elseif ($averageScore >= 80) {
            return 'B';
        } elseif ($averageScore >= 70) {
            return 'C';
        } elseif ($averageScore >= 60) {
            return 'D';
        } elseif ($averageScore >= 50) {
            return 'E';
        } else {
            return 'F';
        }
    }

}
