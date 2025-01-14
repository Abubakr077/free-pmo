<?php

namespace App\Entities\Users;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProject extends Model
{
    protected $fillable = ['user_id', 'project_id'];

    protected $table = 'user_projects';

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
