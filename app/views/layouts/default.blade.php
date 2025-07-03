{{-- Default Layout --}}
<!DOCTYPE html>
<html lang="en">
@include('partials/head')

<body class="g-sidenav-show bg-gray-100">
    {{-- Side Nav Bar --}}
    @include('partials/side-menu')
    {{-- end Side Nav Bar --}}
    {{-- Main Container --}}
    <div class="main-content position-relative max-height-vh-100 h-100">
        {{-- Navbar --}}
        @include('partials/top-menu')
        {{-- End Navbar --}}
        
        @yield('content')

        <footer class="footer py-4  ">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-grey text-lg-start">
                            © <script>
                                document.write(new Date().getFullYear())
                            </script>,
                            made with <i class="fa fa-heart" aria-hidden="true"></i> by
                            <a href="https://www.pasindudissan.xyz" class="font-weight-bold text-black" target="_blank">Pasindu Dissanayaka</a>
                            for Aspirations I-Lab.
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="https://www.pasindudissan.xyz" class="nav-link text-black" target="_blank">Pasindu Dissanayaka</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    {{-- end main container --}}
    @include('partials/footer')
</body>

</html>