@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <!-- Page Heading -->
    {{-- Alert Messages --}}
    @include('common.alert')

    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-3 mt-3">
                <h1 class="h3 mb-0 text-gray-800">Votre profile</h1> 
            </div>
        </div>
        <div class="card-body">

        {{-- Page Content --}}

        <div class="row">
            <div class="col-xl-3">
                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <!-- Profile picture image-->
                    @if(!empty(Auth::user()->avatar))
                        <img class="rounded-circle mt-5" width="150px" src="/admin/img/{{ Auth::user()->avatar }}">
                    @else
                        <img class="rounded-circle mt-5" width="150px" src="{{ asset('admin/img/undraw_profile.svg') }}">
                    @endif
                    <span class="font-weight-bold">{{ auth()->user()->full_name }}</span>
                    <span class="text-black-50"><i>Profile:
                            {{ auth()->user()->roles
                                ? auth()->user()->roles->pluck('name')->first()
                                : 'N/A' }}</i></span>
                    <span class="text-black-50">{{ auth()->user()->email }}</span>
                    <!-- Profile picture upload button-->
                    <button class="btn btn-outline-primary mt-2" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalAvatar">Changer photo de profile</button>
                </div>
            </div>
            <div class="col-xl-9">
                {{-- Profile --}}
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Modifiez vos infos!</h4>
                    </div>
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="row mt-2">
                            <div class="col-xl-6 mt-2">
                                <label class="labels">Prenom</label>
                                <input type="text" class="form-control form-control-user @error('first_name') is-invalid @enderror"
                                    name="first_name" placeholder="Prenom"
                                    value="{{ old('first_name') ? old('first_name') : auth()->user()->first_name }}">

                                @error('first_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-xl-6  mt-2">
                                <label class="labels">Nom</label>
                                <input type="text" name="last_name"
                                    class="form-control form-control-user @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name') ? old('last_name') : auth()->user()->last_name }}"
                                    placeholder="Last Name">

                                @error('last_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-xl-6 mt-2">
                                <label class="labels">Adresse email</label>
                                <input type="text" class="form-control form-control-user @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') ? old('email') : auth()->user()->email }}"
                                    placeholder="Email">
                                @error('mobile_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-xl-6 mt-2">
                                <label class="labels">Numéro de téléphone</label>
                                <input type="text" class="form-control form-control-user @error('mobile_number') is-invalid @enderror" name="mobile_number"
                                    value="{{ old('mobile_number') ? old('mobile_number') : auth()->user()->mobile_number }}"
                                    placeholder="Mobile Number">
                                @error('mobile_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-4 text-center">
                            <div class="col-xl-12">
                                <button class="btn btn-outline-primary profile-button" style="width: 100%;" type="submit">Mettre à jour!</button>
                            </div>
                        </div>
                    </form>
                </div>

                <hr>
                {{-- Change Password --}}
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Changer le mot de passe</h4>
                    </div>

                    <form action="{{ route('profile.change-password') }}" method="POST">
                        @csrf
                        <div class="row mt-2">
                            <div class="col-xl-4 mt-2">
                                <div class="form-floating form-floating-custom auth-pass-inputgroup">
                                    <input type="password" class="form-control form-control-user @error('current_password') is-invalid @enderror" name="current_password" required autocomplete="current_password" id="password-input" placeholder="Mot de passe actuel">
                                    <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                    </button>
                                    <label>Mot de passe actuel</label>
                                    <div class="form-floating-icon">
                                        <i class="uil uil-padlock"></i>
                                    </div>
                                    @error('current_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 mt-2">
                                <div class="form-floating form-floating-custom auth-pass-inputgroup">
                                    <input type="password" class="form-control form-control-user @error('new_password') is-invalid @enderror" name="new_password" required autocomplete="new_password" placeholder="Nouveau mot de passe">
                                    <label>Nouveau mot de passe</label>
                                    @error('new_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 mt-2">
                                <div class="form-floating form-floating-custom auth-pass-inputgroup">
                                    <input type="password" class="form-control form-control-user @error('new_confirm_password') is-invalid @enderror" name="new_confirm_password" required autocomplete="new_confirm_password" placeholder="Confirmer mot de passe">
                                    <label>Confirmation mot de passe</label>
                                    @error('new_confirm_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 text-center">
                            <div class="col-xl-12">
                                <button class="btn btn-outline-primary profile-button" style="width: 100%;" type="submit">Mettre à jour!</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalAvatar" tabindex="-1" role="dialog" aria-labelledby="exampleModalAvatarTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalAvatarTitle">Charger votre photo de profile</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('profile.update_avatar') }}" accept-charset="UTF-8" enctype="multipart/form-data" class="form-horizontal">
                        {{ csrf_field() }}
                            <input type="file" name="avatar" class="form-control">
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit">Valider!</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection