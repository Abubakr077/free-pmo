@extends('layouts.dashboard')

@section('title', __('nav_menu.dashboard'))

@section('content-dashboard')
@if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor'))
<div class="row">
    <div class="col-lg-5">
        <legend style="border-bottom: none" class="text-center">{{ __('dashboard.project_status_stats') }}</legend>
        <div class="row">
            @foreach(ProjectStatus::all() as $statusId => $status)
            <div class="col-lg-6 col-md-4 col-xs-6">
                @include('view-components.dashboard-panel', [
                    'class' => ProjectStatus::getColorById($statusId),
                    'icon' => ProjectStatus::getIconById($statusId),
                    'number' => array_key_exists($statusId, $projectStatusStats) ? $projectStatusStats[$statusId] : 0,
                    'text' => ProjectStatus::getNameById($statusId),
                    'linkRoute' => route('projects.index', ['status_id' => $statusId]),
                ])
            </div>
            @endforeach
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-4 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">{{ __('user.current_jobs') }}</h3></div>
            <table class="table table-condensed">
                <tbody>
                    @php
                        $currentJobTotal = 0;
                    @endphp
                    <tr>
                        <th class="text-center">{{ __('job.progress') }}</th>
                        <th class="text-center">{{ __('user.jobs_count') }}</th>
                    </tr>
                    <tr>
                        <td class="text-center">0 - 10%</td>
                        <td class="text-center">
                            {{ $count = $userCurrentJobs->filter(function ($job) {
                                return $job->progress == 0;
                            })->count() }}
                            @php
                                $currentJobTotal += $count;
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">11 - 50%</td>
                        <td class="text-center">
                            {{ $count = $userCurrentJobs->filter(function ($job) {
                                return $job->progress > 10 && $job->progress <= 50;
                            })->count() }}
                            @php
                                $currentJobTotal += $count;
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">51 - 75%</td>
                        <td class="text-center">
                            {{ $count = $userCurrentJobs->filter(function ($job) {
                                return $job->progress > 50 && $job->progress <= 75;
                            })->count() }}
                            @php
                                $currentJobTotal += $count;
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">76 - 99%</td>
                        <td class="text-center">
                            {{ $count = $userCurrentJobs->filter(function ($job) {
                                return $job->progress > 75 && $job->progress <= 99;
                            })->count() }}
                            @php
                                $currentJobTotal += $count;
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">100%</td>
                        <td class="text-center">
                            {{ $count = $userCurrentJobs->filter(function ($job) {
                                return $job->progress == 100;
                            })->count() }}
                            @php
                                $currentJobTotal += $count;
                            @endphp
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr style="border-top: 4px solid #ccc">
                        <th class="text-center">{{ __('app.total') }}</th>
                        <th class="text-center">{{ $currentJobTotal }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endif
@endsection
