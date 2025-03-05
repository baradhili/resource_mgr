@extends('layouts.app')

@section('template_title')
    {{ $user->name ?? __('Show') . ' ' . __('User') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} User</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">

                        <div class="form-group mb-2 mb20">
                            <strong>Name:</strong>
                            {{ $user->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Email:</strong>
                            {{ $user->email }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Roles:</strong>
                            {{ $user->roles->pluck('name')->implode(', ') }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Resource Group:</strong>
                            {{ $user->currentTeam->name ?? 'None' }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Linked Project Resource?:</strong>
                            @if ($user->resource_id)
                                <a href="{{ route('resources.show', $user->resource_id) }}">Yes</a>
                            @else
                                No
                            @endif
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Reports to:</strong>
                            {{ $user->reportingLine?->name ?? 'None' }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Reportees:</strong>
                            @if ($reportees->isNotEmpty())
                                <ul>
                                    @foreach ($reportees as $reportee)
                                        <li>{!! $reportee->resource_id ? '<a href="'.route('resources.show', $reportee->resource_id).'">'.$reportee->name.'</a>' : $reportee->name !!}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No reportees found for this user.</p>
                            @endif
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
