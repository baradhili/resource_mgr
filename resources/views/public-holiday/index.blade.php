@extends('layouts.app')

@section('template_title')
    Public Holidays
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Public Holidays') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('public-holidays.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Date</th>
									<th >Name</th>
									<th >Region Id</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($publicHolidays as $publicHoliday)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $publicHoliday->date }}</td>
										<td >{{ $publicHoliday->name }}</td>
										<td >{{ $publicHoliday->region_id }}</td>

                                            <td>
                                                <form action="{{ route('public-holidays.destroy', $publicHoliday->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('public-holidays.show', $publicHoliday->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('public-holidays.edit', $publicHoliday->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                <x-pagination :paginator="$publicHolidays" route="public-holidays.index" />
            </div>
        </div>
    </div>
@endsection
