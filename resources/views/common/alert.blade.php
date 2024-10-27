{{-- Message --}}
@if(Session::has('success_message'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <i class="uil uil-check me-2"></i>
            {!! session('success_message') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif(Session::has('error_message'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        <i class="uil uil-exclamation-octagon me-2"></i>
            {!! session('error_message') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
