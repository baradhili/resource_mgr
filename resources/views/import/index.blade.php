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
                                                <form action="{{ route('allocations.upload') }}" method="POST"
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
                                        <td><a href="{{ route('import.review.demands') }}" class="btn btn-primary btn-sm float-right">Review Demands</a> </td>
                                        <td><a  href="{{ route('import.review.allocations') }}" class="btn btn-primary btn-sm float-right">Review Allocations</td>
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
