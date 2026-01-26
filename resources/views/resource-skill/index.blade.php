@extends('layouts.app')

@section('template_title')
    Resource Skills
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Resource Skills') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('resource-skills.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Resources Id</th>
									<th >Skills Id</th>
									<th >Proficiency Levels</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resourceSkills as $resourceSkill)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $resourceSkill->resources_id }}</td>
										<td >{{ $resourceSkill->skills_id }}</td>
										<td >{{ $resourceSkill->proficiency_levels }}</td>

                                            <td>
                                                <form action="{{ route('resource-skills.destroy', $resourceSkill->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('resource-skills.show', $resourceSkill->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('resource-skills.edit', $resourceSkill->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                @include('partials.pagination', ['paginator' => $resourceSkills, 'route' => 'resource-skills.index'])
            </div>
        </div>
    </div>
@endsection
