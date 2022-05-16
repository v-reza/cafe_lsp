@extends('master.home')
@section('title', 'Login')
@section('icon')
<link rel="icon" href="https://cdn-icons-png.flaticon.com/512/1828/1828391.png" type="image/x-icon" />
@endsection
@section('content')
    <div class="container-fluid mt-5" style="margin:auto; width: 1000px; height:500px">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
                    class="img-fluid" alt="Phone image">
            </div>
            <div class="col-md-7 col-lg-7 col-xl-6">
                <div class="text-center">
                    <h2>Multi Login</h2>
                </div>
                <form>
                    @csrf
                    <div class="form-outline mb-4">
                        <input type="text" id="username" class="form-control form-control-lg" placeholder="Username"
                            required />
                    </div>
                    <div class="form-outline mb-4">
                        <input type="password" id="password" class="form-control form-control-lg" placeholder="Password"
                            required />
                    </div>
                    <button type="button" id="myBtn" onclick="prosesLogin()" class="btn btn-primary btn-lg btn-block"
                        style="width: 100%">Sign in</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/auth/login.js') }}"></script>
@endsection
