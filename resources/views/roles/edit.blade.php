 @extends('layouts.app')

@section('title', 'Edit Role')

@section('content')

<div class="container-fluid">

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->

    <div class="card">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-2 mt-2">
            <h1 class="h3 mb-0 text-gray-800">Rôle</h1>
                <a href="{{route('roles.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Retour</a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('roles.update', ['role' => $role->id])}}">
                @csrf
                @method('PUT')
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
                            value="{{ old('name') ? old('name') : $role->name }}">

                        @error('name')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>


                    {{-- Guard Name --}}
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <span style="color:red;">*</span>Guard Name</label>
                        <select class="form-control form-control-user @error('guard_name') is-invalid @enderror" name="guard_name">
                            <option selected disabled>Select Guard Name</option>
                            <option value="web" {{old('guard_name') ? ((old('guard_name') == 'web') ? 'selected' : '') : (($role->guard_name == 'web') ? 'selected' : '')}}>Web</option>
                            <option value="api" {{old('guard_name') ? ((old('guard_name') == 'api') ? 'selected' : '') : (($role->guard_name == 'api') ? 'selected' : '')}}>Api</option>
                        </select>
                        @error('guard_name')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    <div class="col-sm-12 mb-3 mt-3 mb-sm-0">
                        <label> <span style="color:red;">*</span> Permissions</label>
                        <input type="checkbox" name="check-all" class="form-contol" id="checkAllPermissions" {{ (count($permissions) == count($role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}/> All
                        <div class="row">
                            <div class="col-lg-12">
                                @foreach ($permissions as $permissionIndex => $permission)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input permission-input" {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'checked' : '' }} type="checkbox" name="permissions[]" id="inlineCheckbox_{{$permissionIndex}}"  value="{{$permission->id}}">
                                        <label class="form-check-label" for="inlineCheckbox{{$permissionIndex}}">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Save Button --}}
                <div class="card-footer" style="text-align:right">
                    <a class="btn btn-secondary" href="{{ route('roles.index') }}">Annuler</a>
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>

            </form>
        </div>
    </div>

</div>
<script>
    $("#checkAllPermissions").click(function(){
        $('.permission-input').not(this).prop('checked', this.checked);
    });
</script>

@endsection