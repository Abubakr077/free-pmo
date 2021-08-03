@if (Request::get('action') == 'task_edit' && $editableTask)
@can('update', $editableTask)
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-right" style="margin-top: -2px;margin-right: -8px">
            {!! FormField::formButton(
                [
                    'route' => ['tasks.set-as-job', $editableTask],
                    'onsubmit' => __('task.set_as_job_confirm'),
                ],
                __('task.set_as_job'),
                ['class' => 'btn btn-success btn-xs', 'id' => 'set-as-job-'.$editableTask->id]
            ) !!}
        </div>
        <h3 class="panel-title">{{ __('task.edit') }}</h3>
    </div>
    {{ Form::model($editableTask, ['route' => ['tasks.update', $editableTask], 'method' => 'patch']) }}
    <div class="panel-body">
        <div class="row">
            @if(auth()->user()->hasRole('supervisor'))
            <div class="col-sm-6">{!! FormField::text('name') !!}</div>
            @else
                <div class="col-sm-6">{!! FormField::text('name',['readonly' => 'true']) !!}</div>
            @endif
            @can('updateSupervisor',$editableTask)
            <div class="col-md-4">
                {{ Form::label('progress', __('task.progress'), ['class' => 'control-label']) }}

                {{ Form::input('range', 'progress', null, [
                    'min' => '0',
                    'max' => '100',
                    'step' => '10',
                ]) }}
            </div>
            <div class="col-md-2" style="font-size: 28px; margin-top: 15px;">
                <strong id="ap_weight">{{ $editableTask->progress }}</strong>%
            </div>
            @endcan
        </div>
        {!! FormField::textarea('description', ['label' => __('task.description')]) !!}
        <div class="row">
            <div class="col-md-6">
{{--                {!! FormField::select('job_id', $job->project->jobs->pluck('name', 'id'), ['label' => __('task.move_to_other_job')]) !!}--}}
            </div>
            <div class="col-md-6 text-right"><br>
                {{ Form::submit(__('task.update'), ['class' => 'btn btn-warning']) }}
                {{ link_to_route('jobs.show', __('app.cancel'), [$job], ['class' => 'btn btn-default']) }}
            </div>
        </div>
    </div>
    {{ Form::close() }}

</div>
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default table-responsive">
            <div class="panel-heading">
                <h3 class="panel-title">{{ __('project.files') }}</h3>
            </div>
            <table class="table table-condensed table-striped">
                <thead>
                <th>{{ __('app.table_no') }}</th>
                <th>{{ __('file.file') }}</th>
                <th class="text-center">{{ __('file.updated_at') }}</th>
                <th class="text-right">{{ __('file.size') }}</th>
{{--                <th class="text-right">{{ __('Hash') }}</th>--}}
                <th class="text-center">{{ __('file.download') }}</th>
                </thead>
                <tbody class="sort-files">
                                    @forelse($files as $key => $file)
                                        <tr id="{{ $file->id }}">
                                            <td>{{ 1 + $key }}</td>
                                            <td>
                                                <strong class="">{{ $file->title }}</strong>
                                                <div class="text-info small">{{ $file->description }}</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="">{{ $file->getDate() }}</div>
                                                <div class="text-info small">{{ $file->getTime() }}</div>
                                            </td>
                                            <td class="text-right">{{ format_size_units($file->getSize()) }}</td>
{{--                                            <td class="text-right">{{ $file->filename }}</td>--}}
                                            <td class="text-center">
                                                {!! html_link_to_route('files.download', '', [$file->id], ['icon' => 'file', 'title' => __('file.download')]) !!}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6">{{ __('file.empty') }}</td></tr>
                                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4">
{{--        @if (Request::has('action') == false)--}}
        @can('updateStudent',$editableTask)
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">{{ __('file.create') }}</h3></div>
                <div class="panel-body">
                    {!! Form::open(['route' => ['files.upload', $editableTask->id], 'id' => 'upload-file', 'files' => true]) !!}
                    {{ Form::hidden('fileable_type', get_class($editableTask)) }}
                    {!! FormField::file('file', ['label' => __('file.select')]) !!}
                    {!! FormField::text('title') !!}
                    {!! FormField::textarea('description') !!}
                    {!! Form::submit(__('file.upload'), ['class' => 'btn btn-info']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        @endcan
{{--        @endif--}}
    </div>
</div>
@endcan
@endif

@if (Request::get('action') == 'task_delete' && $editableTask)
@can('delete', $editableTask)
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">{{ __('task.delete') }}</h3></div>
    <div class="panel-body">
        <div>{{ $editableTask->name }}</div>
        <div class="small text-info">{!! nl2br($editableTask->description) !!}</div>
    </div>
    <div class="panel-footer">
        {{ __('app.delete_confirm') }}
        {{ link_to_route('jobs.show', __('app.cancel'), [$job], ['class' => 'btn btn-default']) }}
        <div class="pull-right">
            {!! FormField::delete(['route' => ['tasks.destroy', $editableTask]],
                __('app.delete_confirm_button'),
                ['class' => 'btn btn-danger'],
                [
                    'task_id' => $editableTask->id,
                    'job_id' => $editableTask->job_id,
                ]
            ) !!}
        </div>
    </div>
</div>
@endcan
@endif
