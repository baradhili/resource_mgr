@extends('layouts.app')

@section('template_title')
    Demand Requests
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Demand Requests') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('demand-requests.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Project Id</th>
									<th >Requester Id</th>
									<th >Forecast Demand Id</th>
									<th >Allocated Resource Id</th>
									<th >Funded Estimate Id</th>
									<th >Funded By Id</th>
									<th >Client Id</th>
									<th >Status</th>
									<th >Priority</th>
									<th >Role Title</th>
									<th >Quantity Fte</th>
									<th >Start Date</th>
									<th >End Date</th>
									<th >Filled At</th>
									<th >Is Funded</th>
									<th >Funding Source</th>
									<th >Budget Code</th>
									<th >Approved Budget Amount</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($demandRequests as $demandRequest)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $demandRequest->project_id }}</td>
										<td >{{ $demandRequest->requester_id }}</td>
										<td >{{ $demandRequest->forecast_demand_id }}</td>
										<td >{{ $demandRequest->allocated_resource_id }}</td>
										<td >{{ $demandRequest->funded_estimate_id }}</td>
										<td >{{ $demandRequest->funded_by_id }}</td>
										<td >{{ $demandRequest->client_id }}</td>
										<td >{{ $demandRequest->status }}</td>
										<td >{{ $demandRequest->priority }}</td>
										<td >{{ $demandRequest->role_title }}</td>
										<td >{{ $demandRequest->quantity_fte }}</td>
										<td >{{ $demandRequest->start_date }}</td>
										<td >{{ $demandRequest->end_date }}</td>
										<td >{{ $demandRequest->filled_at }}</td>
										<td >{{ $demandRequest->is_funded }}</td>
										<td >{{ $demandRequest->funding_source }}</td>
										<td >{{ $demandRequest->budget_code }}</td>
										<td >{{ $demandRequest->approved_budget_amount }}</td>

                                            <td>
                                                <form action="{{ route('demand-requests.destroy', $demandRequest->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('demand-requests.show', $demandRequest->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('demand-requests.edit', $demandRequest->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $demandRequests->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
