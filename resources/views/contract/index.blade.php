@extends('layouts.app')

@section('template_title')
Contracts
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <span id="card_title">
                            {{ __('Contracts') }}
                        </span>

                        <div class="float-right">
                            <a href="{{ route('contracts.create') }}" class="btn btn-primary btn-sm float-right"
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

                <div class="card-body bg-white">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>Resource</th>

                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Availability</th>
                                    <th>Tenure</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contracts as $contract)
                                    <tr>
                                        <td>{{ $contract->resource->full_name}}</td>
                                        <td>{{ \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') }}</td>
                                        <td>{{ $contract->availability }}</td>
                                        <td>{{ number_format(\Carbon\Carbon::parse($contract->end_date)->floatDiffInYears(\Carbon\Carbon::parse($contract->start_date)), 1) }}</td>

                                        <td>
                                            <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST">
                                                <a class="btn btn-sm btn-primary "
                                                    href="{{ route('contracts.show', $contract->id) }}"><i
                                                        class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('contracts.edit', $contract->id) }}"><i
                                                        class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i
                                                        class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('contracts.clean', ['end_date' => $contract->end_date, 'resource_id' => $contract->resource->id]) }}"><i
                                                        class="fa fa-fw fa-edit"></i> {{ __('Return Projects') }}</a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {!! $contracts->withQueryString()->links() !!}
        </div>
    </div>
</div>
@endsection