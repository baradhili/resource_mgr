@extends('layouts.app')

@section('template_title')
    Import Data
@endsection

@section('content')
    <script>
        function openDialog() {
            document.getElementById('uploadDialog').showModal();
        }

        function closeDialog() {
            document.getElementById('uploadDialog').close();
        }
    </script>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Import Data') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>Importer</th>
                                        <th>Link</th>
                                        <th colspan="2">Reviews</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @can('import.empower')
                                            <td>Empower Data</td>
                                            <td>
                                                <!-- Button to open the dialog -->
                                                <button type="button" class="btn btn-primary btn-sm float-right"
                                                    onclick="openDialog()">
                                                    {{ __('Upload') }}
                                                </button>
                                                <!-- Dialog -->
                                                <dialog id="uploadDialog">
                                                    <!-- 'import.empower' -->
                                                    <form action="{{ route('import.empower') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <h2>{{ __('Upload File') }}</h2>
                                                        <div class="form-group">
                                                            <input type="file" name="file" class="form-control-file"
                                                                required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                onclick="closeDialog()">{{ __('Close') }}</button>
                                                            <button type="submit"
                                                                class="btn btn-primary">{{ __('Upload') }}</button>
                                                        </div>
                                                    </form>
                                                </dialog>
                                            </td>
                                        @endcan
                                        @can('change-requests.index')
                                            <td><a href="{{ route('change-requests.index') }}"
                                                    class="btn btn-primary btn-sm float-right">Review Demands</a> </td>
                                        @endcan
                                    </tr>
                                    <tr>
                                        <td>Public Holidays</td>
                                        <td>
                                            <a href="{{ route('import.holidays') }}"
                                                class="btn btn-primary btn-sm float-right">
                                                {{ __('Upload') }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        @foreach ($plugins as $plugin)
                                            @if ($plugin->type == 'Import')
                                    <tr>
                                        <td>{{ $plugin->displayName }}</td>
                                        <td>
                                            <dialog id="dialog-{{ $plugin->name }}">
                                                <form action="{{ route('import.' . $plugin->name) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            {{ __('Upload') }}
                                                            {{ $plugin->displayName }}
                                                        </h5>
                                                        <button type="button" class="close" onclick="closeDialog()">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="file">
                                                                {{ __('File') }}
                                                            </label>
                                                            <input type="file" name="file" class="form-control-file"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            onclick="closeDialog()">
                                                            {{ __('Close') }}
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            {{ __('Upload') }}
                                                        </button>
                                                    </div>
                                                </form>
                                            </dialog>
                                            <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right"
                                                onclick="openDialog('dialog-{{ $plugin->name }}')">
                                                {{ __('Upload') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
