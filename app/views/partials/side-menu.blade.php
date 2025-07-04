    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand px-4 py-3 m-0" href="/">
                <img src="../../assets/img/logo-ct-dark.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
                <span class="ms-1 text-sm text-dark">Intelligence</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0 mb-2">
        <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item mb-2 mt-0">
                    <a data-bs-toggle="collapse" href="#ProfileNav" class="nav-link text-dark" aria-controls="ProfileNav" role="button" aria-expanded="false">
                        <img src="../../assets/img/team-2.jpg" class="avatar">
                        <span class="nav-link-text ms-2 ps-1">Pasindu Dissanayaka</span>
                    </a>
                    <div class="collapse" id="ProfileNav">
                        <ul class="nav ">
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="/logout">
                                    <span class="sidenav-mini-icon"> L </span>
                                    <span class="sidenav-normal  ms-3  ps-1"> Logout </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <hr class="horizontal dark mt-0">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link text-dark active" role="button">
                        <i class="material-symbols-rounded opacity-5">space_dashboard</i>
                        <span class="nav-link-text ms-1 ps-1">Chat Interface</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-3  ms-2 text-uppercase text-xs font-weight-bolder text-dark">MANAGE</h6>
                </li>
                <li class="nav-item ">
                    <a class="nav-link text-dark " href="/dashboard/history">
                        <span class="sidenav-mini-icon"> H </span>
                        <span class="sidenav-normal  ms-1  ps-1"> History </span>
                    </a>
                </li>                
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/dashboard/analytics">
                        <span class="sidenav-mini-icon"> A </span>
                        <span class="sidenav-normal ms-1 ps-1"> Analytics </span>
                    </a>
                </li>
                <li class="nav-item">
                    <hr class="horizontal dark" />
                    <h6 class="ps-3  ms-2 text-uppercase text-xs font-weight-bolder text-dark">DOCS</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="">
                        <i class="material-symbols-rounded opacity-5">receipt_long</i>
                        <span class="nav-link-text ms-1 ps-1">Changelog</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>