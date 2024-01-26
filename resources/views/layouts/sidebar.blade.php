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
                            <!-- <li class="{{ request()->is('/sent/email*') ? 'active' : '' }}">
                                <a href="/sent/email"><i class="ti-email"></i><span>Sent Email</span></a>
                            </li> -->
                            <!-- <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="ti-pie-chart"></i><span>Charts</span></a>
                                <ul class="collapse">
                                    <li><a href="barchart.html">bar chart</a></li>
                                    <li><a href="linechart.html">line Chart</a></li>
                                    <li><a href="piechart.html">pie chart</a></li>
                                </ul>
                            </li> -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>