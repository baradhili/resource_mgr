@extends('layouts.app')

@section('template_title')
    Change Requests
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Change Requests') }}
                            </span>

                            <div class="float-right d-flex align-items-center">
                                <form action="{{ route('change-requests.index') }}" method="get"
                                    class="d-inline-flex align-items-center" id="filterForm">
                                    <div class="custom-control custom-checkbox mr-2">
                                        <input type="checkbox" class="custom-control-input" id="showHistory" name="history"
                                            value="1" {{ request('history') == 1 ? 'checked' : '' }}
                                            onchange="document.getElementById('filterForm').submit()">
                                        <label class="custom-control-label" for="showHistory">
                                            {{ request('history') == 1 ? 'Show Pending' : 'Show History' }}
                                        </label>
                                    </div>
                                </form>
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
                                            <th>No</th>

                                            <th>Subject</th>

                                            <th>Field</th>
                                            <th>Old Value</th>
                                            <th>New Value</th>
                                            <th>Status</th>


                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($changeRequests as $changeRequest)
                                            <tr>
                                                <td>{{ ++$i }}</td>

                                                <td>{{ $changeRequest->subject }}</td>

                                                <td>{{ $changeRequest->field }}</td>
                                                <td>{{ $changeRequest->old_value }}</td>
                                                <td>{{ $changeRequest->new_value }}</td>
                                                <td>{{ $changeRequest->status }}</td>

                                                <td>
                                                    <form
                                                        action="{{ route('change-requests.destroy', $changeRequest->id) }}"
                                                        method="POST">
                                                        <a class="btn btn-sm btn-primary "
                                                            href="{{ route('change-requests.show', $changeRequest->id) }}"><i
                                                                class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                        @if ($changeRequest->status == 'pending')
                                                            <a class="btn btn-sm btn-success"
                                                                href="{{ route('change-requests.approve', $changeRequest->id) }}"><i
                                                                    class="fa fa-fw fa-edit"></i> {{ __('Approve') }}</a>
                                                            @csrf
                                                            @method('DELETE')
                                                            @can('change-requests.destroy')
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i
                                                                        class="fa fa-fw fa-trash"></i>
                                                                    {{ __('Reject') }}</button>
                                                            @endcan
                                                        @endif

                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <x-pagination :paginator="$changeRequests" route="change-requests.index" />
                </div>
            </div>
        </div>
    </div>
@endsection
