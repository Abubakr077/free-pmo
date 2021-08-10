<?php

namespace App\Http\Controllers\Projects;

use App\Entities\Projects\JobsRepository;
use App\Entities\Projects\Priority;
use App\Entities\Projects\Project;
use App\Entities\Users\User;
use App\Entities\Projects\Invite;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InviteController extends Controller
{
    private $repo;

    public function __construct(JobsRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        $issueQuery = $project->invites()
            ->orderBy('updated_at', 'desc');

        $issues = $issueQuery->get();

        return view('projects.invites.index', compact('project', 'issues'));
    }

    public function pending()
    {
        $issueQuery = auth()->user()->invites()->where('status_id',1)
            ->orderBy('updated_at', 'desc');

        $issues = $issueQuery->get();

        return view('pages.invites', compact( 'issues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        $supervisors =  $this->repo->getSupervisorsList();
        return view('projects.invites.create', compact('project','supervisors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {
        $issueData = $request->validate([
            'message'        => 'required|max:255',
            'supervisor_id' => 'required',
        ]);
        Invite::create([
            'project_id'  => $project->id,
            'supervisor_id'  => $issueData['supervisor_id'],
            'message'        => $issueData['message'],
        ]);
        flash(__('invite.created'), 'success');

        return redirect()->route('projects.invites.index', $project);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function show(Invite $invite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function edit(Invite $invite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invite $invite)
    {
        //
    }
    public function accept( Invite $invite)
    {
        $invite->status_id = 2;
        $invite->save();
        // todo update project here

        flash(__('invite.updated'), 'success');

        return redirect(route('auth.pending-invites'));
    }
    public function reject( Invite $invite)
    {
        $invite->status_id = 3;
        $invite->save();

        flash(__('invite.updated'), 'success');

        return redirect(route('auth.pending-invites'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invite  $invite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invite $invite)
    {
        //
    }
}
