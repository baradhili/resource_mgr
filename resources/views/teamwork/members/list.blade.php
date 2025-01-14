@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Members of team "') }}{{ $team->name }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('teams.index') }}" class="btn btn-primary btn-sm float-right"
                                    data-placement="left">
                                    {{ __('Back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
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
    </div>
@endsection
