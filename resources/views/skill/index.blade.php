@extends('layouts.app')

@section('template_title')
    Skills
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Skills') }}
                            </span>

                            <div class="float-right">
                                @can('skills.upload')
                                    <button type="button" class="btn btn-primary btn-sm float-right" onclick="openDialog()">
                                        {{ __('Upload') }}
                                    </button>
                                    <script>
                                        function openDialog() {
                                            document.getElementById('uploadDialog').showModal();
                                        }

                                        function closeDialog() {
                                            document.getElementById('uploadDialog').close();
                                        }
                                    </script>
                                    <dialog id="uploadDialog">
                                        <form action="{{ route('skills.upload') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <h2>{{ __('Upload File') }}</h2>
                                            <div class="form-group">
                                                <input type="file" name="files[]" class="form-control-file" required multiple
                                                    accept=".json">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    onclick="closeDialog()">{{ __('Close') }}</button>
                                                <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
                                            </div>
                                        </form>
                                    </dialog>
                                @endcan
                                @can('skills.create')
                                    <a href="{{ route('skills.create') }}" class="btn btn-primary btn-sm float-right"
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
                                        <th>No</th>

                                        <th>Skill Name</th>
                                        <th>Skill Description</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($skills as $skill)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $skill->skill_name }}</td>
                                            <td>{{ $skill->skill_description }}</td>

                                            <td>
                                                <form action="{{ route('skills.destroy', $skill->id) }}" method="POST">
                                                    @can('skills.show')
                                                        <a class="btn btn-sm btn-primary "
                                                            href="{{ route('skills.show', $skill->id) }}"><i
                                                                class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    @endcan
                                                    @can('skills.edit')
                                                        <a class="btn btn-sm btn-success"
                                                            href="{{ route('skills.edit', $skill->id) }}"><i
                                                                class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('skills.destory')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i
                                                                class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
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
                <x-pagination :paginator="$skills" route="skills.index" />
            </div>
        </div>
    </div>
@endsection
