@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Members of team "{{ $team->name }}"
                        <a href="{{ route('teams.index') }}" class="btn btn-sm btn-default pull-right">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
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
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
