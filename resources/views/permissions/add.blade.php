@extends('layouts.app')

@section('title', 'Add Permission')

@section('content')
    
    {{-- Alert Messages --}}
    @include('common.alert')

    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Ajout de la permission</h1>
                <a href="{{route('permissions.index')}}" class="btn btn-outline-primary"><i
                class="fas fa-arrow-left fa-sm text-blue-50"></i></a>
            </div>
        </div>

        <div class="card-body">
            <form method="POST" action="{{route('permissions.store')}}">
                @csrf
                <div class="form-group row">

                    {{-- Name --}}
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Name</label>
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
                <div class="modal-footer mt-4" style="text-align:right">
                    <a class="btn btn-outline-danger" href="{{ route('permissions.index') }}">Annuler</a>
                    &nbsp;&nbsp; 
                    <button type="submit" class="btn btn-outline-primary">Valider</button>
                </div>
            </form>
        </div>
    </div>
@endsection