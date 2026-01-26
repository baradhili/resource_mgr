@extends('layouts.app')

@section('template_title')
    Services
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Services') }}
                            </span>

                            <div class="float-right">
                                @can('services.create')
                                    <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm float-right"
                                        data-placement="left">
                                        {{ __('Create New') }}
                                    </a>
                                @endcan
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

                                        <th>Service Name</th>

                                        <th>Required Skills</th>
                                        <th>Hours Cost</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $service)
                                        <tr>

                                            <td>{{ $service->service_name }}</td>
                                            <td>{{ implode(', ', $service->required_skills) }}</td>

                                            <td>{{ $service->hours_cost }}</td>

                                            <td>
                                                <form action="{{ route('services.destroy', $service->id) }}" method="POST">
                                                    @can('services.show')
                                                        <a class="btn btn-sm btn-primary "
                                                            href="{{ route('services.show', $service->id) }}"><i
                                                                class="fas fa-eye"></i> {{ __('Show') }}</a>
                                                    @endcan
                                                    @can('services.create')
                                                        <a class="btn btn-sm btn-success"
                                                            href="{{ route('services.edit', $service->id) }}"><i
                                                                class="fas fa-edit"></i> {{ __('Edit') }}</a>
                                                    @endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('services.create')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i
                                                                class="fas fa-trash"></i> {{ __('Delete') }}</button>
                                                    @endcan
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @include('partials.pagination', ['paginator' => $services, 'route' => 'services.index'])
            </div>
        </div>
    </div>
@endsection
