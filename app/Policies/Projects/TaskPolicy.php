<?php

namespace App\Policies\Projects;

use App\Entities\Projects\Task;
use App\Entities\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create tasks.
     *
     * @param \App\Entities\Users\User    $user
     * @param \App\Entities\Projects\Task $task
     *
     * @return mixed
     */
    public function create(User $user, Task $task)
    {
        return $user->hasRole('admin') || $user->hasRole('supervisor');
    }

    /**
     * Determine whether the user can update the task.
     *
     * @param \App\Entities\Users\User    $user
     * @param \App\Entities\Projects\Task $task
     *
     * @return mixed
     */
    public function update(User $user, Task $task)
    {
        return $user->hasRole('admin')
            || $user->hasRole('worker')
            || $user->hasRole('supervisor')
            || ($user->hasRole('student')
                && $task->job->worker_id == $user->id);
    }

    public function updateStudent(User $user, Task $task)
    {
        return
            $user->hasRole('admin')
            || $user->hasRole('worker')
            || ($user->hasRole('student')
                && $task->job->worker_id == $user->id);
    }
    public function updateSupervisor(User $user, Task $task)
    {
        return
            $user->hasRole('admin')
            || $user->hasRole('worker')
            || ($user->hasRole('supervisor'));
    }

    /**
     * Determine whether the user can delete the task.
     *
     * @param \App\Entities\Users\User    $user
     * @param \App\Entities\Projects\Task $task
     *
     * @return mixed
     */
    public function delete(User $user, Task $task)
    {
        return $user->hasRole('admin');
    }
}
