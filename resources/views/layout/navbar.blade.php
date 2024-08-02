 <!-- BEGIN: Header-->
 <nav
     class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
     <div class="navbar-container d-flex content">
         <div class="bookmark-wrapper d-flex align-items-center">
             <ul class="nav navbar-nav d-xl-none">
                 <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon"
                             data-feather="menu"></i></a></li>
             </ul>
         </div>
         <ul class="nav navbar-nav align-items-center ms-auto">
             <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                     id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                     aria-expanded="false">
                     <div class="user-nav d-sm-flex d-none">
                        @if (Auth::user()->role_id == 1)
                            <span lass="user-name fw-bolder">{{ auth()->user()->name }}</span>
                            <span class="user-status">{{ role(auth()->user()->role_id) }}</span>
                        @else
                            <span lass="user-name fw-bolder">{{ auth()->user()->name }}</span>
                            <span class="user-status">{{ role(auth()->user()->role_id) }}</span>
                            <span class="user-status">Outlet: {{  auth()->user()->outlet->name  }}</span>
                        @endif
                    </div>
                        <span class="avatar"><img class="round"
                             src="{{ asset('assets') }}/app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar"
                             height="40" width="40"><span class="avatar-status-online"></span></span>
                 </a>
                 <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                     <a class="dropdown-item" href="{{ url('/logout') }}"><i class="me-50" data-feather="power"></i>
                         Logout</a>
                 </div>
             </li>
         </ul>
     </div>
 </nav>
