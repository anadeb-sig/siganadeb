@extends('auth.layouts.app')

@section('title', 'Register')

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
                                <h5 class="mb-0">Créer un compte</h5>
                                <p class="text-muted mt-2">Obtenez votre compte gratuit maintenant.</p>
                            </div>
                            <form class="mt-4 pt-2" method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus id="input-email" placeholder="Enter Email">
                                    <div class="invalid-feedback">
                                        Please Enter Email
                                    </div> 
                                    <label for="input-email">Email</label>
                                    <div class="form-floating-icon">
                                        <i class="uil uil-envelope-alt"></i>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus id="input-username" placeholder="Prénom de l'utilisateur">
                                    <label for="input-username">Prénom de l'utilisateur</label>
                                    <div class="form-floating-icon">
                                        <i class="uil uil-users-alt"></i>
                                    </div>
                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus id="input-username" placeholder="Nom de l'utilisateur">
                                    <label for="input-username">Nom de l'utilisateur</label>
                                    <div class="form-floating-icon">
                                        <i class="uil uil-users-alt"></i>
                                    </div>
                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" id="password-input" placeholder="Mot de passe">
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
                                <div class="form-floating form-floating-custom mb-3">
                                    <input id="password-confirm" type="password" class="form-control" placeholder="Confirmation mot de passe" name="password_confirmation" required autocomplete="new-password">                           
                                    <label for="input-password">Confirmation mot de passe</label>
                                    <div class="form-floating-icon">
                                        <i class="uil uil-padlock"></i>
                                    </div>
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-success w-100" type="submit">Enregistrer</button>
                                </div>

                                <div class="mt-4 pt-3 text-center">
                                    <p class="text-muted mb-0">Vous avez déjà un compte ? <a href="{{ route('login')}}" class="text-muted fw-semibold text-decoration-none"> &emsp;Connexion </a> </p>
                                </div>
                            </form>
                            <!-- end form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end col -->
@endsection
