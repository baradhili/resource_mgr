@extends('layouts.app')

@section('template_title')
    Terms And Conditions
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Terms And Conditions') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('terms-and-conditions.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Payment Terms</th>
									<th >Validity</th>
									<th >Assumptions</th>
									<th >Change Management</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($termsAndConditions as $termsAndCondition)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $termsAndCondition->payment_terms }}</td>
										<td >{{ $termsAndCondition->validity }}</td>
										<td >{{ $termsAndCondition->assumptions }}</td>
										<td >{{ $termsAndCondition->change_management }}</td>

                                            <td>
                                                <form action="{{ route('terms-and-conditions.destroy', $termsAndCondition->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('terms-and-conditions.show', $termsAndCondition->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('terms-and-conditions.edit', $termsAndCondition->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                @include('partials.pagination', ['paginator' => $termsAndConditions, 'route' => 'terms-and-conditions.index'])
            </div>
        </div>
    </div>
@endsection
