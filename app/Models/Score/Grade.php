<?php

namespace App\Models\Score;

use App\Models\User\UserModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Grade extends Model
{
    use HasFactory;
    protected $fillable = ['score_id', 'grade', 'type_id'];

    public function user()
    {
        return $this->belongsTo(UserModel::class,'type_id');
    }

    public function score()
    {
        return $this->belongsTo(Score::class,'score_id');
    }
}
