<?php

namespace App\Models\Score;

use App\Models\Subject\Subject;
use App\Models\User\Type;
use App\Models\User\UserModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Score extends Model
{
    use HasFactory;

    protected $fillable = ['type_id', 'subject_id', 'score','value'];
    protected $table= 'score';

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'type_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    
    public function grade()
    {
        return $this->hasMany(Grade::class,'score_id');
    }
}
