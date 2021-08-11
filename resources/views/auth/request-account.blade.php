@inject('roles', 'App\Entities\Users\Role')

@extends('layouts.guest')

@section('title', __('auth.reset_password'))

@section('content')

        <div class="login-panel panel panel-default">
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    {!! Form::open(['route'=>'auth.store-account']) !!}
                    <div class="panel panel-default">
                        <div class="panel-heading"><h3 class="panel-title">{{ __('user.create') }}</h3></div>
                        <div class="panel-body">
                            {!! FormField::text('name', ['label' => __('app.name')]) !!}
                            {!! FormField::email('email', ['label' => __('user.email')]) !!}
                            {!! FormField::checkboxes('role', $roles::guestArray(), ['label' => __('user.role')]) !!}
{{--                            {{ Form::hidden('is_approved', 1) }}--}}

                            {!! FormField::password('password', [
                                'label' => __('auth.password'),
                                'info' => [
                                    'text' => __('user.create_password_info'),
                                    'class' => 'info',
                                ],
                            ]) !!}
                        </div>
                        <div class="panel-footer">
                            {!! Form::submit(__('Request Account'), ['class'=>'btn btn-primary']) !!}

                            {!! link_to_route('auth.login', __('Back'), [], ['class'=>'btn btn-default']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

@endsection
