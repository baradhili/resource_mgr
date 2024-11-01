@extends('layouts.app')

@section('template_title')
Allocations
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
                            {{ __('Allocations') }}
                        </span>

                        <div class="float-right">
                            <!-- Button to open the dialog -->
                            <button type="button" class="btn btn-primary btn-sm float-right" onclick="openDialog()">
                                {{ __('Upload') }}
                            </button>

                            <!-- Dialog -->
                            <dialog id="uploadDialog">
                                <form action="{{ route('allocations.upload') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <h2>{{ __('Upload File') }}</h2>
                                    <div class="form-group">
                                        <input type="file" name="file" class="form-control-file" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            onclick="closeDialog()">{{ __('Close') }}</button>
                                        <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
                                    </div>
                                </form>
                            </dialog>

                            <a href="{{ route('allocations.create') }}" class="btn btn-primary btn-sm float-right"
                                data-placement="left">
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

                                    <th>Year</th>
                                    <th>Month</th>
                                    <th>Fte</th>
                                    <th>Resources Id</th>
                                    <th>Projects Id</th>
                                    <th>Status</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                               
                                    <tr>
                                       

                                        <td>
                                            
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