@extends('layouts.app')

@section('template_title')
    Dashboard
@endsection

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12 d-flex">
                <div class="w-100">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    {{ Widget::AverageWorkload() }}
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    {{ Widget::ContractTenure() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    {{ Widget::SkillSetCoverage() }}
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    {{ Widget::UnrecoveredCosts() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    {{ Widget::UpcomingDemand() }}
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    {{ Widget::ResourceAvailability() }}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    {{ Widget::DemandFunnel() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
