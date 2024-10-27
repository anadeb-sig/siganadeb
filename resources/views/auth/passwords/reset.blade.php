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
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Réinitialisation du mot de passe!</h1>
                            </div>

                            @if (session('error'))
                                <span class="text-danger"> {{ session('error') }}</span>
                            @endif
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">
                                
                                <div class="form-floating form-floating-custom mb-3 auth-pass-inputgroup">
                                    <input id="email" type="email"
                                        class="form-control form-control-user @error('email') is-invalid @enderror"
                                        name="email" value="{{ $email ?? old('email') }}" required
                                        autocomplete="email" autofocus placeholder="Enter Email Address.">
                                    <label for="password-input">Entrez votre adresse mail</label>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-floating form-floating-custom mb-3 auth-pass-inputgroup">
                                    <input id="password" type="password"
                                        class="form-control form-control-user @error('password') is-invalid @enderror"
                                        name="password" required autocomplete="new-password" placeholder="New Password">
                                    <!-- <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                    </button> -->
                                    <label for="password-input">Entrez nouveau Mot de passe</label>
                                    <div class="form-floating-icon">
                                        <i class="uil uil-padlock"></i>
                                    </div>

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-floating form-floating-custom mb-3 auth-pass-inputgroup">
                                    <input id="password-confirm" type="password" class="form-control form-control-user"
                                        name="password_confirmation" required autocomplete="new-password"
                                        placeholder="Confirm Password">

                                    <!-- <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                    </button> -->
                                    <label for="password-input">Confirmez nouveau mot de passe</label>
                                    <div class="form-floating-icon">
                                        <i class="uil uil-padlock"></i>
                                    </div>
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <button class="btn w-100" type="submit" style="background-color: #5f9ea0; color: #ffff;">{{ __('Valider pour réinitialiser') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
