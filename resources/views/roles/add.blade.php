@extends('layouts.app')

@section('title', 'Ajout')

@section('content')

{{-- Alert Messages --}}
@include('common.alert')
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
            <h1 class="h3 mb-0 text-gray-800">Ajout du rôle</h1>
            <a href="{{route('roles.index')}}" class="btn btn-outline-primary"><i
                    class="fas fa-arrow-left fa-sm text-blue-50"></i></a>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{route('roles.store')}}">
            @csrf
            <div class="form-group row">

                {{-- Name --}}
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <span style="color:red;">*</span>Nom</label>
                    <input 
                        type="text" 
                        class="form-control form-control-user @error('name') is-invalid @enderror" 
                        id="exampleName"
                        placeholder="Name" 
                        name="name" 
                        value="{{ old('name') }}">

                    @error('name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>


                {{-- Email --}}
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <span style="color:red;">*</span>Guard Name</label>
                    <select class="form-control form-control-user @error('guard_name') is-invalid @enderror" name="guard_name">
                        <option selected disabled>Select Guard Name</option>
                        <option value="web" selected>Web</option>
                        <option value="api">Api</option>
                    </select>
                    @error('guard_name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>

            </div>

            {{-- Save Button --}}
            <div class="modal-footer mt-4">
                <a class="btn btn-outline-danger" href="{{ route('roles.index') }}">Annuler</a>
                &nbsp;&nbsp; 
                <button type="submit" class="btn btn-outline-primary">Valider</button>
            </div>

        </form>
    </div>

</div>


@endsection