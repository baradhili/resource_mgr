@extends('layouts.app')

@section('template_title')
    Review Demands
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Review Demands</span>
                    </div>
                    <div class="card-body">
                        @if (count($changes) > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Date</th>
                                        <th>Resource</th>
                                        <th>Old FTEs</th>
                                        <th>New FTEs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($changes as $change)
                                        <tr>
                                            <td>{{ $change['project'] }}</td>
                                            <td>{{ $change['date']->format('M-Y') }}</td>
                                            <td>{{ $change['resource'] }}</td>
                                            <td>{{ $change['old_ftes'] }}</td>
                                            <td>{{ $change['new_ftes'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No changes detected.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
