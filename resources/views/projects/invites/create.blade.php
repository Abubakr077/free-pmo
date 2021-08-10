@extends('layouts.project')

@section('subtitle', __('invite.create'))

@section('action-buttons')
@can('create', new App\Entities\Projects\Issue)
    {!! html_link_to_route('projects.invites.create', __('invite.create'), $project, ['class' => 'btn btn-success', 'icon' => 'plus']) !!}
@endcan
@endsection

@section('content-project')

<div class="row">
    <div class="col-sm-6 col-sm-offset-2">
        {{ Form::open(['route' => ['projects.invites.store', $project]]) }}
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">{{ __('invite.create') }}</h3></div>
            <div class="panel-body">
                {!! FormField::select('supervisor_id', $supervisors, ['label' => __('Supervisor'), 'value' => 1]) !!}
                {!! FormField::textarea('message', ['label' => __('invite.message')]) !!}
            </div>
            <div class="panel-footer">
                {{ Form::submit(__('invite.create'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('projects.invites.index', __('app.cancel'), $project, ['class' => 'btn btn-default']) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
@endsection
