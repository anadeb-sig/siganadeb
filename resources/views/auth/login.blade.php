@extends('auth.layouts.app')

@section('title', 'Login')

@section('content')

<div class="col-xxl-9 col-lg-8 col-md-7">
    <div class="auth-bg bg-light py-md-5 p-4 d-flex">
        <div class="bg-overlay-gradient"></div>
        <!-- end bubble effect -->
        <div class="row justify-content-center g-0 align-items-center w-100">
            <div class="col-xl-4 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="px-3 py-3">
                            <div class="text-center">
                                <h5 class="mb-0">Content de vous revoir !</h5>
                                <p class="text-muted mt-2">Connectez-vous pour continuer.</p>
                            </div>

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="mt-4 pt-2">
                                @csrf
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email Address." id="input-username" placeholder="Enter User Name">
                                    <label for="input-username">Email</label>
                                    <div class="form-floating-icon">
                                        <i class="uil uil-users-alt"></i>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-floating form-floating-custom mb-3 auth-pass-inputgroup">
                                    <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" id="password-input" placeholder="Enter Password">
                                    <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                    </button>
                                    <label for="password-input">Mot de passe</label>
                                    <div class="form-floating-icon">
                                        <i class="uil uil-padlock"></i>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <button class="btn w-100" type="submit" style="background-color: #5f9ea0; color: #ffff;">Connexion</button>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('password.request') }}" class="btn w-100" type="submit" style="background-color: beige;color: #76abae;font-weight: 600;">Mot de passe oublié?</a>
                                </div>
                            </form><!-- end form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection