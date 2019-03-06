@extends('layouts.project')

@section('subtitle', __('issue.detail'))

@section('content-project')

<div class="row">
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">{{ __('issue.detail') }}</h3></div>
            <table class="table table-condensed">
                <tbody>
                    <tr><th class="col-md-4">{{ __('issue.title') }}</th><td class="col-md-8">{{ $issue->title }}</td></tr>
                    <tr><th>{{ __('issue.body') }}</th><td>{{ $issue->body }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
