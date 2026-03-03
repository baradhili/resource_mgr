@extends('layouts.app')

@section('template_title')
    Forecast Demands
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Forecast Demands') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('forecast-demands.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Opportunity Id</th>
									<th >Owner Id</th>
									<th >Stage</th>
									<th >Status</th>
									<th >Win Probability</th>
									<th >Client Id</th>
									<th >Start Year</th>
									<th >Start Quarter</th>
									<th >Duration Months</th>
									<th >Estimated Budget</th>
									<th >Notes</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($forecastDemands as $forecastDemand)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $forecastDemand->opportunity_id }}</td>
										<td >{{ $forecastDemand->owner_id }}</td>
										<td >{{ $forecastDemand->stage }}</td>
										<td >{{ $forecastDemand->status }}</td>
										<td >{{ $forecastDemand->win_probability }}</td>
										<td >{{ $forecastDemand->client_id }}</td>
										<td >{{ $forecastDemand->start_year }}</td>
										<td >{{ $forecastDemand->start_quarter }}</td>
										<td >{{ $forecastDemand->duration_months }}</td>
										<td >{{ $forecastDemand->estimated_budget }}</td>
										<td >{{ $forecastDemand->notes }}</td>

                                            <td>
                                                <form action="{{ route('forecast-demands.destroy', $forecastDemand->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('forecast-demands.show', $forecastDemand->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('forecast-demands.edit', $forecastDemand->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $forecastDemands->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
