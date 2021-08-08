<?php

namespace App\Entities\Projects;

use Illuminate\Database\Eloquent\Model;



class Task extends Model
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => 'App\Events\Tasks\Created',
        'updated' => 'App\Events\Tasks\Updated',
        'deleted' => 'App\Events\Tasks\Deleted',
    ];



    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $touches = ['job'];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
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
