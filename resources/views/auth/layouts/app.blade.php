<!DOCTYPE html>
<html lang="fr">

{{-- Head Before AUTH--}}
@include('auth.includes.head')
<body class="bg-white">
    <div class="auth-page d-flex align-items-center min-vh-100">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-xxl-3 col-lg-4 col-md-5">
                    <div class="d-flex flex-column h-100 py-5 px-4">
                        <div class="text-center text-muted mb-2">
                            <div class="pb-3">
                                <a href="{{ route('home')}}">
                                    <span class="logo-lg">
                                        <img src="{{asset('assets/images/ANADEB.png') }}" alt="ANADEB" height="96"> <span class="logo-txt"></span>
                                    </span>
                                </a>
                                <p class="text-muted font-size-15 w-75 mx-auto mt-3 mb-0" style="color: #139d38 !important;">{{__('Agence Nationale d\'Appui au Développement à la Base')}}</p>
                            </div>
                        </div>
                        <div class="my-auto">
                            <!-- <div class="p-3 text-center">
                                <img src="{{asset('assets/images/auth-img.jpg') }}" alt="" class="img-fluid">
                            </div> -->
                        </div>
                        <div class="mt-4 mt-md-5 text-center">
                            <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> ANADEB <i class="mdi mdi-heart text-danger"></i><a href="https://anadeb.org" style="color:#378557;" target="_blank">@ANADEB.ORG</a></p>
                        </div>
                    </div>                    
                    <!-- end auth full page content -->
                </div>
                {{-- Content Goes Here FOR Before AUTH --}}
                @yield('content')
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container fluid -->
    </div>

    {{-- Scripts Before AUTH --}}
    @include('auth.includes.scripts')

</body>

</html>