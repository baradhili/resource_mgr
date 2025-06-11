@extends('layouts.app')

@section('template_title')
    Import Data
@endsection

@section('content')
    <script>
        function openDialog(dialogId) {
            document.getElementById(dialogId).showModal();
        }

        function closeDialog(dialogId) {
            document.getElementById(dialogId).close();
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

                    @can('change-requests.index')
                        <a href="{{ route('change-requests.index') }}"
                            class="btn btn-primary btn-sm float-right">{{ __('Review Changes') }}</a> 
                    @endcan
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
                                                                    {{ __('Upload') }} {{ $plugin->displayName }}
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    onclick="closeDialog('dialog-{{ $plugin->name }}')">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="file">
                                                                        {{ __('File') }}
                                                                    </label>
                                                                    <input type="file" name="file"
                                                                        class="form-control-file" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    onclick="closeDialog('dialog-{{ $plugin->name }}')">
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
