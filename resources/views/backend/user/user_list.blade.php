@extends('backend.layouts.app')
@section('title', 'user list')
@section('extra_css')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white me-2">
                        <i class="mdi mdi-account-multiple"></i>
                    </span>User List
                </h3>
            </div>
            <div class="row my-3">
                <a class="col-2 text-decoration-none text-white btn bg-gradient-primary" href="{{ route('users.create') }}">
                    <i class="mdi mdi-account-plus"></i>
                    </span>Add User
                </a>
            </div>
            <div class="row ">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered display DataTables nowrap" style="width:100%">
                            <thead class="bg-gradient-primary">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>IP</th>
                                    <th>User Agent</th>
                                    <th>Login At</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
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
                ajax: '/backend/users/datatable/serverData',
                processing: true,
                serverSide: true,
                columns: [

                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email',
                       
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'ip',
                        name: 'ip'
                    },
                    {
                        data: 'user_agent',
                        name: 'user_agent',
                        sortable :false,
                        searchable: false
                    },
                    {
                        data: 'login_at',
                        name: 'login_at'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        sortable :false,
                        searchable: false
                    },
                ],
                order: [
                  [7, "desc"]
                ]


            });

            $(document).on('click', '.delete', function(e) {
                e.preventDefault();

                var id = $(this).data('id')
                var userURL = $(this).data('url');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: userURL,
                            type: 'DELETE',
                            success: function() {
                                table.ajax.reload();
                            }

                        })
                    }
                })

            })



        })
    </script>
@endsection
