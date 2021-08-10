@extends('layouts.dashboard')

@section('title', __('nav_menu.dashboard'))

@section('content-dashboard')
<div class="row">
    <div class="col-lg-12">
        <div id="project-issues" class="panel panel-default table-responsive">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('project.invites') }}</h3>
            </div>
            <table class="table table-condensed table-striped">
                <thead>
                <th>{{ __('app.table_no') }}</th>
                <th>{{ __('Owner') }}</th>
                <th>{{ __('invite.message') }}</th>
                <th>{{ __('app.status') }}</th>
                <th>{{ __('app.last_update') }}</th>
                <th class="text-center">{{ __('app.action') }}</th>
                </thead>
                <tbody>
                @forelse($issues as $key => $issue)
                    @php
                        $key=0;/** @var int $no */
                        $no = 1 + $key;
                    @endphp
                    <tr id="{{ $issue->id }}">
                        <td>{{ $no }}</td>
                        <td>{{ $issue->owner() }}</td>
                        <td>{{ $issue->message }}</td>
                        <td>{!! $issue->getStatus() !!}</td>
                        <td>{{ $issue->updated_at->diffForHumans() }}</td>
                        <td class="text-center">
                        {!! html_link_to_route('projects.invites.accept', '', [$issue],
                        ['icon' => 'check-circle-o', 'class' => 'btn btn-success btn-xs', 'title' => 'Accept']) !!}
                        {!! html_link_to_route('projects.invites.reject', '', [$issue],
                        ['icon' => 'ban', 'class' => 'btn btn-danger btn-xs', 'title' => 'Reject']) !!}
                    </tr>
                @empty
                    <tr><td colspan="6">{{ __('invite.not_found') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
