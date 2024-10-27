@extends('layouts.app')

@section('title', 'Add Users')

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Utilisateur</h1>
                <a href="{{route('users.index')}}" class="btn btn-outline-primary"><i
                class="fas fa-arrow-left fa-sm text-blue-50"></i></a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('users.store')}}">
                @csrf
                <div class="card-body">
                    <div class="form-group row">

                        {{-- First Name --}}
                        <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                            <span style="color:red;">*</span>Prénom(s)</label>
                            <input 
                                type="text" 
                                class="form-control form-control-user @error('first_name') is-invalid @enderror" 
                                id="exampleFirstName"
                                placeholder="First Name" 
                                name="first_name" 
                                value="{{ old('first_name') }}">

                            @error('first_name')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                            <span style="color:red;">*</span>Nom</label>
                            <input 
                                type="text" 
                                class="form-control form-control-user @error('last_name') is-invalid @enderror" 
                                id="exampleLastName"
                                placeholder="Last Name" 
                                name="last_name" 
                                value="{{ old('last_name') }}">

                            @error('last_name')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                            <span style="color:red;">*</span>Email</label>
                            <input 
                                type="email" 
                                class="form-control form-control-user @error('email') is-invalid @enderror" 
                                id="exampleEmail"
                                placeholder="Email" 
                                name="email" 
                                value="{{ old('email') }}">

                            @error('email')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        {{-- Mobile Number --}}
                        <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                            <span style="color:red;">*</span>Numéro de téléphone</label>
                            <input 
                                type="text" 
                                class="form-control form-control-user @error('mobile_number') is-invalid @enderror" 
                                id="exampleMobile"
                                placeholder="Mobile Number" 
                                name="mobile_number" 
                                value="{{ old('mobile_number') }}">

                            @error('mobile_number')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                            <span style="color:red;">*</span>Role</label>
                            <select class="form-control form-control-user @error('role_id') is-invalid @enderror" name="role_id">
                                <option selected disabled>Selectionner le role</option>
                                @foreach ($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                            <span style="color:red;">*</span>Status</label>
                            <select class="form-control form-control-user @error('status') is-invalid @enderror" name="status">
                                <option selected disabled>Selectionner le status</option>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="card-footer" style="text-align:right">
                    <a class="btn btn-outline-danger" href="{{ route('users.index') }}">Annuler</a>
                    <button type="submit" class="btn btn-outline-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>

</div>


@endsection