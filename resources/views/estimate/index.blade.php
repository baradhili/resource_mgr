@extends('layouts.app')

@section('template_title')
    Estimates
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Estimates') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('estimates.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Name</th>
									<th >Expiration Date</th>
									<th >Tags</th>
									<th >Estimate Owner</th>
									<th >Partner</th>
									<th >Total Cost</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($estimates as $estimate)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $estimate->name }}</td>
										<td >{{ $estimate->expiration_date }}</td>
										<td >{{ $estimate->tags }}</td>
										<td >{{ $estimate->estimate_owner }}</td>
										<td >{{ $estimate->partner }}</td>
										<td >{{ $estimate->total_cost }}</td>
                                            <td>
                                                <form action="{{ route('estimates.destroy', $estimate->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('estimates.show', $estimate->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('estimates.edit', $estimate->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                <x-pagination :paginator="$estimates" route="estimates.index" />
            </div>
        </div>
    </div>
@endsection
