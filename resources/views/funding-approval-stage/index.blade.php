@extends('layouts.app')

@section('template_title')
    Funding Approval Stages
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Funding Approval Stages') }}
                            </span>

                            <div class="float-right">
                                @can('funding-approval-stages.create')<a href="{{ route('funding-approval-stages.create') }}"
                                    class="btn btn-primary btn-sm float-right" data-placement="left">
                                    {{ __('Create New') }}@endcan
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

                                        <th>Stage Name</th>
                                        <th>Description</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fundingApprovalStages as $fundingApprovalStage)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $fundingApprovalStage->stage_name }}</td>
                                            <td>{{ $fundingApprovalStage->description }}</td>

                                            <td>
                                                <form
                                                    action="{{ route('funding-approval-stages.destroy', $fundingApprovalStage->id) }}"
                                                    method="POST">
                                                    <!-- <a class="btn btn-sm btn-primary "
                                                        href="{{ route('funding-approval-stages.show', $fundingApprovalStage->id) }}"><i
                                                            class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a> -->
                                                   @can('funding-approval-stages.edit') <a class="btn btn-sm btn-success"
                                                        href="{{ route('funding-approval-stages.edit', $fundingApprovalStage->id) }}"><i
                                                            class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>@endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('funding-approval-stages.destory')<button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i
                                                            class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>@endcan
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $fundingApprovalStages->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
