@extends('layouts.project')

@section('subtitle', __('project.invites'))

@section('action-buttons')
{{ Form::open(['method' => 'get', 'class' => 'form-inline', 'style' => 'display:inline']) }}
{{--{!! FormField::select('status_id', $issueStatuses::toArray(), ['label' => false, 'placeholder' => __('invite.all_status'), 'value' => request('status_id')]) !!}--}}
{{--{{ Form::submit(__('app.filter'), ['class' => 'btn btn-info']) }}--}}
@isset($project)
@if (request(['priority_id', 'status_id']))
    {{ link_to_route('projects.invites.index', __('app.reset'), $project, ['class' => 'btn btn-default']) }}
@endif
{{ Form::close() }}
@can('create', new App\Entities\Projects\Invite)
    {!! html_link_to_route('projects.invites.create', __('invite.create'), $project, ['class' => 'btn btn-success', 'icon' => 'plus']) !!}
@endcan
@endisset
@endsection

@section('content-project')
<div id="project-issues" class="panel panel-default table-responsive">
    <div class="panel-heading">
        <h3 class="panel-title">{{ __('project.invites') }}</h3>
    </div>
    <table class="table table-condensed table-striped">
        <thead>
            <th>{{ __('app.table_no') }}</th>
            <th>{{ __('Supervisor') }}</th>
            <th>{{ __('invite.message') }}</th>
            <th>{{ __('app.status') }}</th>
            <th>{{ __('app.last_update') }}</th>
{{--            <th class="text-center">{{ __('app.action') }}</th>--}}
        </thead>
        <tbody>
            @forelse($issues as $key => $issue)
            @php
                $key=0;/** @var int $no */
                $no = 1 + $key;
            @endphp
            <tr id="{{ $issue->id }}">
                <td>{{ $no }}</td>
                <td>{{ $issue->supervisor() }}</td>
                <td>{{ $issue->message }}</td>
                <td>{!! $issue->getStatus() !!}</td>
                <td>{{ $issue->updated_at->diffForHumans() }}</td>
{{--                <td class="text-center">--}}
{{--                    {{ link_to_route(--}}
{{--                        'projects.invites.show',--}}
{{--                        __('app.show'),--}}
{{--                        [$project, $issue],--}}
{{--                        ['title' => __('invite.show')]--}}
{{--                    ) }}--}}
{{--                </td>--}}
            </tr>
            @empty
            <tr><td colspan="9">{{ __('invite.not_found') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
