<nav class="navbar navbar-main navbar-expand-lg mt-2 top-1 px-0 py-1 mx-3 shadow-none border-radius-lg" id="navbarBlur" data-scroll="true">
    <div class="container-fluid py-1 px-2">
        <div class="sidenav-toggler sidenav-toggler-inner d-xl-block d-none ">
            <a href="javascript:;" class="nav-link text-body p-0">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
            </a>
        </div>
        <nav aria-label="breadcrumb" class="ps-2">
            <ol class="breadcrumb bg-transparent mb-0 p-0">
                <li class="breadcrumb-item text-sm text-dark active font-weight-bold" aria-current="page">@yield('title')</li>
            </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="input-group input-group-outline">
                    <label class="form-label">Search here</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item">
                    <a href="../../pages/authentication/signin/illustration.html" class="px-1 py-0 nav-link line-height-0" target="_blank">
                        <i class="material-symbols-rounded">
                            account_circle
                        </i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:;" class="nav-link py-0 px-1 line-height-0">
                        <i class="material-symbols-rounded fixed-plugin-button-nav">
                            settings
                        </i>
                    </a>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>