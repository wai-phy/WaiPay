@extends('backend.layouts.app')
@section('title', 'Wallet list')
@section('extra_css')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-wallet"></i>
                    </span>Wallet List
                </h3>
            </div>
            <div class="row ">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered display DataTables nowrap" style="width:100%">
                            <thead class="bg-gradient-primary">
                                <tr>
                                    <th>Account Number</th>
                                    <th>Account Person</th>
                                    <th>Amount (MMK)</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <span class=" d-block text-center text-sm-start d-sm-inline-block">@Copyright 2023 By Wai Phyo Aung</span>
                <span class="float-none float-sm-end mt-1 mt-sm-0 text-end"> Free <a
                        href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin
                        template</a> from Bootstrapdash.com</span>
            </div>
        </footer>
        <!-- partial -->
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var table = new DataTable('.DataTables', {
                ajax: '/backend/wallet/datatable/serverData',
                processing: true,
                serverSide: true,
                columns: [

                    {
                        data: 'account_number',
                        name: 'account_number'
                    },
                    {
                        data: 'account_person',
                        name: 'account_person',
                       
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                       
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    
                ],
                order: [
                  [4, "desc"]
                ]


            });

           
        })
    </script>
@endsection
