<?php

namespace App\Entities\Projects;

use App\Entities\BaseRepository;
use App\Entities\Partners\Customer;
use App\Entities\Users\User;
use App\Entities\Users\UserProject;
use DB;
use Illuminate\Support\Str;
use ProjectStatus;

/**
 * Projects Repository Class.
 */
class ProjectsRepository extends BaseRepository
{
    protected $model;

    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    public function getProjects($q, $statusId, User $user)
    {
        $statusIds = array_keys(ProjectStatus::toArray());

        if ($user->hasRole('admin') == false) {
            $projects = $user->projects()
                ->where(function ($query) use ($q, $statusId, $statusIds) {
                    $query->where('projects.name', 'like', '%'.$q.'%');

                    if ($statusId && in_array($statusId, $statusIds)) {
                        $query->where('status_id', $statusId);
                    }
                })
                ->latest()
                ->with([ 'jobs'])
                ->paginate($this->_paginate);
            if ($projects->isEmpty()){
                $projects = Project::where('user_id',$user->id)
                    ->where(function ($query) use ($q, $statusId, $statusIds) {
                        $query->where('projects.name', 'like', '%'.$q.'%');

                        if ($statusId && in_array($statusId, $statusIds)) {
                            $query->where('status_id', $statusId);
                        }
                    })
                    ->latest()
                    ->with([ 'jobs'])
                    ->paginate($this->_paginate);
            }
            return $projects;
        }

        return $this->model->latest()
            ->where(function ($query) use ($q, $statusId, $statusIds) {
                $query->where('name', 'like', '%'.$q.'%');

                if ($statusId && in_array($statusId, $statusIds)) {
                    $query->where('status_id', $statusId);
                }
            })
            ->paginate($this->_paginate);
    }

    public function create($projectData)
    {

        $user_id = auth()->user()->id;;
        $projectData['user_id'] =  $user_id;

//        $projectData['customer_id'] = $projectData['supervisor_id'];
//        $projectData['customer_name'] = $projectData['supervisor_name'];
//        $projectData['customer_email'] = $projectData['supervisor_email'];
//        DB::beginTransaction();
//
//        if (isset($projectData['customer_id']) == false || $projectData['customer_id'] == '') {
//            $customer = $this->createNewCustomer($projectData['customer_name'], $projectData['customer_email']);
//            $projectData['customer_id'] = $customer->id;
//        }
//        unset($projectData['customer_name']);
//        unset($projectData['customer_email']);

        $project = $this->storeArray($projectData);
        DB::commit();


        UserProject::create([
            'user_id' => $user_id,
            'project_id' => $project->id
        ]);

        return $project;
    }

    public function getStatusName($statusId)
    {
        return ProjectStatus::getNameById($statusId);
    }

    public function createNewCustomer($customerName, $customerEmail)
    {
        $newCustomer = new Customer();
        $newCustomer->name = $customerName;
        $newCustomer->email = $customerEmail;
        $newCustomer->save();

        return $newCustomer;
    }

    public function delete($projectId)
    {
        $project = $this->requireById($projectId);

        DB::beginTransaction();

        // Delete project payments
        $project->payments()->delete();

        // Delete jobs tasks
        $jobIds = $project->jobs->pluck('id')->all();
        DB::table('tasks')->whereIn('job_id', $jobIds)->delete();

        // Delete jobs
        $project->jobs()->delete();

        // Delete project
        $project->delete();

        DB::commit();

        return 'deleted';
    }

    public function updateStatus($statusId, $projectId)
    {
        $project = $this->requireById($projectId);
        $project->status_id = $statusId;
        $project->save();

        return $project;
    }

    public function jobsReorder($sortedData)
    {
        $jobOrder = explode(',', $sortedData);
        foreach ($jobOrder as $order => $jobId) {
            $job = $this->requireJobById($jobId);
            $job->position = $order + 1;
            $job->save();
        }

        return $jobOrder;
    }
}
