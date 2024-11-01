@extends('layouts.app')

@section('template_title')
    Resources and Availability
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Resources and Availability') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('resources.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >Full Name</th>
                                        <!-- Dynamically add columns for the next twelve months -->
                                        @foreach ($nextTwelveMonths as $month)
                                            <th>{{ $month['monthName'] }} {{ $month['year'] }}</th>
                                        @endforeach
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resources as $resource)
                                        <tr>
                                            
										<td >{{ $resource->full_name }}</td>
                                            <!-- Populate availability for each month -->
                                            @foreach ($nextTwelveMonths as $month)
                                                @php
                                                    $monthKey = $month['year'] . '-' . str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                                                    $availability = $resourceAvailability[$resource['id']]['availability'][$monthKey] ?? '-';
                                                @endphp
                                                <td>{{ $availability }}</td>
                                            @endforeach
                                            <td>
                                                <form action="{{ route('resources.destroy', $resource->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('resources.show', $resource->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('resources.edit', $resource->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $resources->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
