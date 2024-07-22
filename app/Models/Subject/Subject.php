<?php

namespace App\Models\Subject;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subject';

    public function subject():BelongsTo{
        return $this->belongsTo(Type::class, 'subject_id','id');
    }
}
