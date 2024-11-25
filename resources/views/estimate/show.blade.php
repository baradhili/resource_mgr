@extends('layouts.app')

@section('template_title')
    {{ $estimate->name ?? __('Show') . ' ' . __('Estimate') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <style>
            .estimate-header {
                margin-bottom: 2rem;
            }

            .estimate-header h1 {
                margin-bottom: 0.5rem;
            }

            .estimate-header .details {
                font-size: 1.2rem;
            }

            .estimate-header .total-cost {
                font-size: 2rem;
                font-weight: bold;
                margin-top: 1rem;
                text-align: right;
            }

            .estimate-section {
                margin-bottom: 2rem;
            }

            .estimate-section h2 {
                margin-bottom: 1rem;
            }

            .estimate-section table {
                width: 100%;
                margin-bottom: 1rem;
            }

            .estimate-section table th,
            .estimate-section table td {
                padding: 0.5rem;
                border: 1px solid #dee2e6;
                vertical-align: top;
            }

            .estimate-section table th {
                background-color: #f8f9fa;
            }

            .estimate-section .total-cost {
                font-size: 2rem;
                font-weight: bold;
                margin-top: 1rem;
            }
        </style>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body bg-white">
                            <div class="estimate-header">
                                <h1>Estimate: Project XYZ</h1>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="details">
                                            <p><strong>Client:</strong> John Doe</p>
                                            <p><strong>Client Organisation:</strong> RTIO/Rail</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="details">
                                            <p><strong>Business Partner:</strong> Jane Smith</p>
                                            <p><strong>Estimator:</strong> Mike Johnson</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="total-cost text-end">
                                    Total Cost: $5,000
                                </div>
                            </div>

                            <div class="estimate-section">
                                <h2>Scope of Works</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt
                                    ut labore et dolore magna aliqua.</p>
                            </div>

                            <div class="estimate-section">
                                <h2>Itemized Services</h2>
                                <table>
                                    <thead>
                                        <tr>
                                            <th style="width: 80%;">Service</th>
                                            <th>Size</th>
                                            <th>Subtotal Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Service A</td>
                                            <td>Small</td>
                                            <td>$1,000</td>
                                        </tr>
                                        <tr>
                                            <td>Service B</td>
                                            <td>Large</td>
                                            <td>$1,500</td>
                                        </tr>
                                        <tr>
                                            <td>Service C</td>
                                            <td>Medium</td>
                                            <td>$1,200</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" style="border: none;"><strong>Subtotal:</strong></td>
                                            <td style="border-top: 2px solid #000;">$3,700</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border: none;"><strong>Overheads:</strong></td>
                                            <td>$1,000</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border: none;"><strong>Total:</strong></td>
                                            <td style="border-top: 2px solid #000; border-bottom: double 2px #000;">$5,000</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="estimate-section">
                                <h2>Assumptions</h2>
                                <ul>
                                    <li>Lorem ipsum dolor sit amet.</li>
                                    <li>Consectetur adipiscing elit.</li>
                                </ul>
                            </div>

                            <div class="estimate-section">
                                <h2>Risks</h2>
                                <ul>
                                    <li>Ut enim ad minim veniam.</li>
                                    <li>Quis nostrud exercitation ullamco.</li>
                                </ul>
                            </div>

                            <div class="estimate-section">
                                <h2>Terms and Conditions</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt
                                    ut labore et dolore magna aliqua.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
