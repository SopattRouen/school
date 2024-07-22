<?php

namespace App\Models\User;

use App\Models\Score\Grade;
use App\Models\Score\Score;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory,Notifiable,HasApiTokens;
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    protected $table='user';
    public function type():BelongsTo{
        return $this->belongsTo(Type::class,'type_id','id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class,'type_id');
    }

    public function grade(){
        return $this->hasMany(Grade::class,'type_id');
    }

}
