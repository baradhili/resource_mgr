@extends('layouts.app')

@section('template_title')
    Staging Allocations
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Staging Allocations') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('staging-allocations.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Allocation Date</th>
									<th >Fte</th>
									<th >Resources Id</th>
									<th >Projects Id</th>
									<th >Status</th>
									<th >Source</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stagingAllocations as $stagingAllocation)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $stagingAllocation->allocation_date }}</td>
										<td >{{ $stagingAllocation->fte }}</td>
										<td >{{ $stagingAllocation->resources_id }}</td>
										<td >{{ $stagingAllocation->projects_id }}</td>
										<td >{{ $stagingAllocation->status }}</td>
										<td >{{ $stagingAllocation->source }}</td>

                                            <td>
                                                <form action="{{ route('staging-allocations.destroy', $stagingAllocation->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('staging-allocations.show', $stagingAllocation->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('staging-allocations.edit', $stagingAllocation->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $stagingAllocations->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
