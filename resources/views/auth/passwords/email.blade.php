@extends('auth.layouts.app')

@section('title', 'Forgot Password')

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
                            @if (session('error'))
                                <span class="text-danger"> {{ session('error') }}</span>
                            @endif

                            <div class="alert font-size-14 alert-success text-center mb-3 mt-5" role="alert">
                                Entrez votre e-mail et des instructions vous seront envoyées par e-mail.
                            </div>

                            <form method="POST" action="{{ route('password.email') }}" class="mt-3"> 
                                @csrf

                                <div class="form-floating form-floating-custom mb-3">
                                    <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email Address.">
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
                                <div class="mt-4">
                                    <button class="btn w-100" type="submit" style="background-color: #5f9ea0; color: #ffff;">{{ __('Valider') }}</button>
                                </div>
                            </form>
                            <div class="mt-3 text-center">
                                <p class="text-muted mb-0">Avez-vous le mot de passe?<a href="{{ route('login')}}" class="text-muted fw-semibold text-decoration-underline"> 
Connectez-vous</a> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection