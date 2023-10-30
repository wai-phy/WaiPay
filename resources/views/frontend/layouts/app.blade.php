<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    {{-- bootstrap link  --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    {{-- font awesome link  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Google Font (Open Sans) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&display=swap" rel="stylesheet">

    {{-- customize css link  --}}
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

    {{-- date range picker css link --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('customize_css')
</head>

<body>

    <div id="app">
        <div class="header-menu">
            <div class="d-flex justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-2 text-center">
                            @if (!request()->is('/'))
                                <a class="back-btn" href="#">
                                    <i class="fa-solid fa-angle-left"></i>
                                </a>
                            @endif
                        </div>
                        <div class="col-8 text-center">

                                <h3>@yield('title')</h3>

                        </div>
                        <div class="col-2 text-center">
                            <a href="">
                                <i class="fa-solid fa-bell"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    @yield('content')
                </div>
            </div>
        </div>

        <div class="bottom-menu">
            <div class="scan-tab">
                <a href="{{route('scan_and_pay')}}">
                    <div class="inside">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                </a>
            </div>
            <div class="d-flex justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-3 text-center">
                            <a href="{{ route('home') }}">
                                <i class="fa-solid fa-house-chimney"></i>
                                <p>Home</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="{{route('wallet')}}">
                                <i class="fa-solid fa-wallet"></i>
                                <p>Wallet</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="{{route('transaction')}}">
                                <i class="fa-solid fa-right-left"></i>
                                <p>Transition</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="{{ route('profile') }}">
                                <i class="fa-solid fa-user"></i>
                                <p>Account</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- bootstrap js  --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    {{-- jquery link  --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    {{-- sweet alert box  --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- jscroll pagination --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>

    {{-- date range picker link --}}
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


    <script>
        $(document).ready(function() {
            let token = document.head.querySelector('meta[name="csrf-token"]')
            if (token) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF_TOKEN': token.content
                        // 'Content-Type' : 'application/json',
                        // 'Accept' : 'application/json',
                    }
                });
            }

            $('.back-btn').on('click', function() {
                window.history.go(-1);
                return false;
            })

            @if (session('create'))
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('create') }}",
                    showConfirmButton: false,
                    timer: 1500
                })
            @endif

            @if (session('update'))
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('update') }}",
                    showConfirmButton: false,
                    timer: 1500,
                    width: 500,
                    height: 20,
                })
            @endif


        })
    </script>
    @yield('scripts')
</body>

</html>
