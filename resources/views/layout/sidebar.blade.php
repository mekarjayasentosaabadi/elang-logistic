<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto  w-75">
                <a class="navbar-brand" href="{{ url('/') }}"><span class="w-100 ">
                        <img src="{{ asset('assets') }}/img/logo.png" alt=""
                            style="width: 100%;object-fit: contain" class="mx-auto" />

                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            @foreach (listMenu(Auth::user()->role_id) as $item)
                @if ($item['hasChild'])
                    <li
                        class=" nav-item {{ in_array(cekUri(Route::current()->uri()), $item['url']) ? 'sidebar-group-active open' : '' }}">
                        <a class="d-flex align-items-center" href="#"><i
                                data-feather="{{ $item['icon'] }}"></i><span class="menu-title text-truncate"
                                data-i18n="Dashboards">{{ $item['title'] }}</span></a>
                        <ul class="menu-content">
                            @foreach ($item['child'] as $subItem)
                                <li class="{{ cekUri(Route::current()->uri()) == $subItem['url'] ? 'active' : '' }}"><a
                                        class="d-flex align-items-center" href="{{ url($subItem['url']) }}"><i
                                            data-feather="{{ $subItem['icon'] }}"></i><span
                                            class="menu-item text-truncate"
                                            data-i18n="{{ $subItem['title'] }}">{{ $subItem['title'] }}</span></a>
                                </li>
                            @endforeach

                        </ul>
                    </li>
                @else
                    <li class="{{ cekUri(Route::current()->uri()) == $item['url'] ? 'active' : '' }} nav-item"><a
                            class="d-flex align-items-center" href="{{ url($item['url']) }}"><i
                                data-feather="{{ $item['icon'] }}"></i><span class="menu-title text-truncate"
                                data-i18n="{{ $item['title'] }}">{{ $item['title'] }}</span></a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
