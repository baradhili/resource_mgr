@extends('layouts.app')

@section('template_title')
    Teams
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Teams') }}
                            </span>
                            <div class="float-right">
                                <a href="{{ route('teams.create') }}" class="btn btn-primary btn-sm float-right"
                                    data-placement="left">
                                    {{ __('Create New') }}
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
                                    <th>Resource Type</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teams as $team)
                                    <tr>
                                        <td>{{ $team->name }}</td>

                                        <td>{{ $team->resource_type ?? 'N/A' }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary "
                                                href="{{ route('teams.members.show', $team->id) }}"><i
                                                    class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>

                                            @if (auth()->user()->isOwnerOfTeam($team))
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('teams.edit', $team->id) }}"><i
                                                        class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>


                                                <form style="display: inline-block;"
                                                    action="{{ route('teams.destroy', $team) }}" method="post">
                                                    {!! csrf_field() !!}
                                                    <input type="hidden" name="_method" value="DELETE" />
                                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i>
                                                        Delete</button>
                                                </form>
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
