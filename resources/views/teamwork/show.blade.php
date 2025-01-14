@extends('layouts.app')

@section('template_title')
    {{ $team->name ?? __('Show') . ' ' . __('Team') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ $team->name }} Team</span>
                        </div>
                        
                    </div>

                    <div class="card-body bg-white">

                        <div class="form-group mb-2 mb20">
                            <strong>Team Resource Manager:</strong>
                            {{ $team->owner->name }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Resource Type:</strong>
                            {{ $team->resource_type }}
                        </div>
                        <p><strong>Members:</strong></p>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($team->users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            @if (auth()->user()->isOwnerOfTeam($team))
                                                @if (auth()->user()->getKey() !== $user->getKey())
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ route('teams.leader', [$team->id, $user->id]) }}">
                                                    <i class="fa fa-fw fa-user"></i> {{ __('Make Leader') }}</a>
                                                    <form style="display: inline-block;"
                                                        action="{{ route('teams.members.destroy', [$team, $user]) }}"
                                                        method="post">
                                                        {!! csrf_field() !!}
                                                        <input type="hidden" name="_method" value="DELETE" />
                                                        <button class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>
                                                            Delete</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
