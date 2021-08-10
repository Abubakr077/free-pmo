@extends('layouts.project')

@section('subtitle', __('invite.detail'))

@section('action-buttons')
@can('create', new App\Entities\Projects\Issue)
    {!! html_link_to_route('projects.invites.create', __('invite.create'), $project, ['class' => 'btn btn-success', 'icon' => 'plus']) !!}
@endcan
@endsection

@section('content-project')
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <div class="pull-right">{!! $issue->status_label !!}</div>
                    {{ __('invite.detail') }}
                </h3>
            </div>
            <table class="table table-condensed">
                <tbody>
                    <tr><th class="col-md-4">{{ __('Supervisor') }}</th><td class="col-md-8">{{ $issue->supervisor() }}</td></tr>
                    <tr><th>{{ __('invite.message') }}</th><td>{{ $issue->body }}</td></tr>
                    <tr><th>{{ __('invite.priority') }}</th><td>{!! $issue->priority_label !!}</td></tr>
                    <tr><th>{{ __('invite.pic') }}</th><td>{{ $issue->pic->name }}</td></tr>
                    <tr><th>{{ __('app.created_by') }}</th><td>{{ $issue->creator->name }}</td></tr>
                </tbody>
            </table>
            <div class="panel-footer">
                {{ link_to_route('projects.invites.edit', __('invite.edit'), [$project, $issue], ['id' => 'edit-invite-'.$issue->id, 'class' => 'btn btn-warning']) }}
                {{ link_to_route('projects.invites.index', __('invite.back_to_index'), [$project], ['class' => 'btn btn-default pull-right']) }}
            </div>
        </div>
        <hr>
        @include('projects.invites.partials.comment-section')
    </div>
{{--    <div class="col-md-6">--}}
{{--        {{ Form::model($issue, ['route' => ['invites.options.update', $issue], 'method' => 'patch']) }}--}}
{{--        <div class="panel panel-default">--}}
{{--            <div class="panel-heading"><h3 class="panel-title">{{ __('app.action') }}</h3></div>--}}
{{--            <div class="panel-body">--}}
{{--                {!! FormField::radios('priority_id', $priorities, ['label' => __('invite.priority')]) !!}--}}
{{--                {!! FormField::radios('status_id', $statuses, ['label' => __('app.status')]) !!}--}}
{{--                {!! FormField::select('pic_id', $users, ['label' => __('invite.assign_pic'), 'placeholder' => __('invite.select_pic')]) !!}--}}
{{--            </div>--}}
{{--            <div class="panel-footer">--}}
{{--                {{ Form::submit(__('invite.update'), ['class' => 'btn btn-success']) }}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        {{ Form::close() }}--}}
{{--    </div>--}}
</div>
@endsection

@section('script')
<script>
(function () {
    $('#commentModal').modal({
        show: true,
        backdrop: 'static',
    });
})();
</script>
@endsection
