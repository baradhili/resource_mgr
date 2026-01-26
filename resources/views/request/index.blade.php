@extends('layouts.app')

@section('template_title')
    Requests
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Requests') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('requests.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Demand Type Id</th>
									<th >Product Group Function Domain Id</th>
									<th >Site Id</th>
									<th >Business Partner</th>
									<th >Request Title</th>
									<th >Background</th>
									<th >Business Need</th>
									<th >Problem Statement</th>
									<th >Specific Requirements</th>
									<th >Funding Approval Stage Id</th>
									<th >Wbs Number</th>
									<th >Expected Start</th>
									<th >Expected Duration</th>
									<th >Business Value</th>
									<th >Business Unit</th>
									<th >Additional Expert Contact</th>
									<th >Attachments</th>
									<th >Resource Type</th>
									<th >Fte</th>
									<th >Status</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requests as $request)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $request->demand_type_id }}</td>
										<td >{{ $request->product_group_function_domain_id }}</td>
										<td >{{ $request->site_id }}</td>
										<td >{{ $request->business_partner }}</td>
										<td >{{ $request->request_title }}</td>
										<td >{{ $request->background }}</td>
										<td >{{ $request->business_need }}</td>
										<td >{{ $request->problem_statement }}</td>
										<td >{{ $request->specific_requirements }}</td>
										<td >{{ $request->funding_approval_stage_id }}</td>
										<td >{{ $request->wbs_number }}</td>
										<td >{{ $request->expected_start }}</td>
										<td >{{ $request->expected_duration }}</td>
										<td >{{ $request->business_value }}</td>
										<td >{{ $request->business_unit }}</td>
										<td >{{ $request->additional_expert_contact }}</td>
										<td >{{ $request->attachments }}</td>
										<td >{{ $request->resource_type }}</td>
										<td >{{ $request->fte }}</td>
										<td >{{ $request->status }}</td>

                                            <td>
                                                <form action="{{ route('requests.destroy', $request->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('requests.show', $request->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('requests.edit', $request->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                <x-pagination :paginator="$requests" route="requests.index" />
            </div>
        </div>
    </div>
@endsection
