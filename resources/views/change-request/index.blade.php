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

                             <div class="float-right">
                                <a href="{{ route('change-requests.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        <th>No</th>
                                        
									<th >Record Type</th>
									<th >Record Id</th>
									<th >Field</th>
									<th >Old Value</th>
									<th >New Value</th>
									<th >Status</th>


                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($changeRequests as $changeRequest)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $changeRequest->record_type }}</td>
										<td >{{ $changeRequest->record_id }}</td>
										<td >{{ $changeRequest->field }}</td>
										<td >{{ $changeRequest->old_value }}</td>
										<td >{{ $changeRequest->new_value }}</td>
										<td >{{ $changeRequest->status }}</td>

                                            <td>
                                                <form action="{{ route('change-requests.destroy', $changeRequest->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('change-requests.show', $changeRequest->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('change-requests.approve', $changeRequest->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Approve') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Reject') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $changeRequests->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
