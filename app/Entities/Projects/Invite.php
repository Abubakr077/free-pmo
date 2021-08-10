<?php

namespace App\Entities\Projects;

use App\Entities\Partners\Customer;
use App\Entities\Users\User;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{

    protected $fillable = [
        'project_id', 'message', 'status_id', 'supervisor_id'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function supervisor()
    {
        $user = User::where('id', $this->supervisor_id)->first();
        return $user->name;
    }

    public function getStatus()
    {
        $STATUSES = [
            1 => 'Pending',
            2 => 'Accepted',
            3 => 'Rejected',
        ];
        return $STATUSES[$this->status_id];
    }

}
