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
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Resource type</th>
                                        <th>Old FTE</th>
                                        <th>New FTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($changes as $change)
                                        <tr>
                                            <td>{{ $change['project'] }}</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $change['start'])->format('M-Y') }}</td>
                                            <td>
                                                @if ($change['start'] !== $change['end'])
                                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $change['end'])->format('M-Y') }}
                                                @endif
                                            </td>
                                            <td>{{ $change['resource'] }}</td>
                                            <td>{{ number_format(round($change['old_ftes'], 2), 2) }}</td>
                                            <td>{{ number_format(round($change['new_ftes'], 2), 2) }}</td>
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
