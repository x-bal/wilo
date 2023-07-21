<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Faceid App') }} | Login</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- ================== BEGIN core-css ================== -->
    <link href="{{ asset('/') }}css/vendor.min.css" rel="stylesheet" />
    <link href="{{ asset('/') }}css/apple/app.min.css" rel="stylesheet" />
    <link href="{{ asset('/') }}plugins/ionicons/css/ionicons.min.css" rel="stylesheet" />
    <!-- ================== END core-css ================== -->
</head>

<body class='pace-top'>
    <!-- BEGIN #loader -->
    <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div>
    <!-- END #loader -->


    <!-- BEGIN #app -->
    <div id="app" class="app">
        <!-- BEGIN login -->
        <div class="login login-v2 fw-bold">
            <!-- BEGIN login-cover -->
            <div class="login-cover">
                <div class="login-cover-img" style="background-image: url({{ asset('/') }}img/login-bg/login-bg-17.jpg)" data-id="login-cover-image"></div>
                <div class="login-cover-bg"></div>
            </div>
            <!-- END login-cover -->

            <!-- BEGIN login-container -->
            <div class="login-container">
                <!-- BEGIN login-header -->
                <div class="login-header">
                    <img src="{{ asset('/') }}img/logo/wilo.png" alt="" class="logo" width="200" style="margin-left: 75px !important;">
                </div>
                <!-- END login-header -->

                <!-- BEGIN login-content -->
                <div class="login-content">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="form-floating mb-20px">
                            <input type="email" class="form-control fs-13px h-45px border-0" placeholder="Email Address" id="emailAddress" name="email" />
                            <label for="emailAddress" class="d-flex align-items-center text-gray-600 fs-13px">Email Address</label>

                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-floating mb-20px">
                            <input type="password" class="form-control fs-13px h-45px border-0" placeholder="Password" name="password" />
                            <label for="password" class="d-flex align-items-center text-gray-600 fs-13px">Password</label>

                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-20px">
                            <button type="submit" class="btn btn-success d-block w-100 h-45px btn-lg">Login</button>
                        </div>
                    </form>
                </div>
                <!-- END login-content -->
            </div>
            <!-- END login-container -->
        </div>
        <!-- END login -->

        <!-- BEGIN login-bg -->
        <div class="login-bg-list clearfix">
            <div class="login-bg-list-item active">
                <a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="{{ asset('/') }}img/login-bg/login-bg-17.jpg" style="background-image: url({{ asset('/') }}img/login-bg/login-bg-17.jpg)"></a>
            </div>
        </div>
        <!-- END login-bg -->

        <!-- BEGIN scroll-top-btn -->
        <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
        <!-- END scroll-top-btn -->
    </div>
    <!-- END #app -->

    <!-- ================== BEGIN core-js ================== -->
    <script src="{{ asset('/') }}js/vendor.min.js"></script>
    <script src="{{ asset('/') }}js/app.min.js"></script>
    <script src="{{ asset('/') }}js/theme/apple.min.js"></script>
    <!-- ================== END core-js ================== -->

    <!-- ================== BEGIN page-js ================== -->
    <script src="{{ asset('/') }}js/demo/login-v2.demo.js"></script>
    <!-- ================== END page-js ================== -->
</body>

</html>