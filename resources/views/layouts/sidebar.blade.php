<div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="/"><img src="{{ asset('assets/images/icon/logo.png')}}" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li class="{{ request()->is('/') ? 'active' : '' }}">
                                <a href="/"><i class="ti-dashboard"></i><span>Keyword List</span></a>
                            </li>
                            <li class="{{ request()->is('email/list') ? 'active' : '' }}">
                                <a href="/email/list"><i class="ti-email"></i><span>Email List</span></a>
                            </li>
                            {{-- <li class="{{ request()->is('setting') ? 'active' : '' }}">
                                <a href="/setting"><i class="ti-id-badge"></i><span>Setting</span></a>
                            </li> --}}
                        </ul>
                    </nav>
                </div>
            </div>
        </div>