@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Role
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Role</span>
                    </div>
                    <div class="card-body bg-white">
                        <form action="{{ url('roles/' . $role->id . '/give-permissions') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                @error('permission')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <label for="">Permissions</label>

                                <div class="row">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-2">
                                            <label>
                                                <input type="checkbox" name="permission[]" value="{{ $permission->name }}"
                                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
