@extends('layouts.app')

@section('title', 'Edit Permission')

@section('content')

<div class="container-fluid">

    {{-- Alert Messages --}}
    @include('common.alert')

    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
                <h1 class="h3 mb-0 text-gray-800">Permission</h1>
                <a href="{{route('permissions.index')}}" class="btn btn-outline-primary"><i
                class="fas fa-arrow-left fa-sm text-blue-50"></i></a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('permissions.update', $permission->id)}}" accept-charset="UTF-8" id="form" class="form-horizontal">
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
                            value="{{ old('name') ? old('name') : $permission->name }}">

                        @error('name')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>


                    {{-- Guard Name --}}
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Guard Name</label>
                        <select class="form-control form-control-user @error('guard_name') is-invalid @enderror" name="guard_name">
                            <option selected disabled>Select Guard Name</option>
                            <option value="web" {{old('guard_name') ? ((old('guard_name') == 'web') ? 'selected' : '') : (($permission->guard_name == 'web') ? 'selected' : '')}}>Web</option>
                            <option value="api" {{old('guard_name') ? ((old('guard_name') == 'api') ? 'selected' : '') : (($permission->guard_name == 'api') ? 'selected' : '')}}>Api</option>
                        </select>
                        @error('guard_name')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                </div>

                {{-- Save Button --}}
                <div class="card-footer" style="text-align:right">
                    <a class="btn btn-outline-danger" href="{{ route('permissions.index') }}">Annuler</a>
                    <button type="submit" class="btn btn-outline-primary">Editer</button>
                </div>

            </form>
        </div>
    </div>

</div>


@endsection