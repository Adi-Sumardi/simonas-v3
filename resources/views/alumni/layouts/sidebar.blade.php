<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div class="h-100">

        <div class="user-wid text-center py-4">
            <div class="user-img">
                <img src="images/users/avatar-2.jpg" alt="" class="avatar-md mx-auto rounded-circle">
            </div>

            <div class="mt-3">

                <a href="#" class="text-dark font-weight-medium font-size-16">{{\illuminate\Support\Facades\Auth::user()->name}}</a>
                <p class="text-body mt-1 mb-0 font-size-13">{{\illuminate\Support\Facades\Auth::user()->asrama}}</p>

            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

                <li style="background: linear-gradient(135deg, #007BFF, #0056b3); border-radius: 10px;">
                    <a href="javascript: void(0);" class="waves-effect text-white">
                        <i class="mdi mdi-airplay text-white"></i>
                        <span class="text-white">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="#" class=" waves-effect">
                        <i class="mdi mdi-library-books"></i>
                        <span>Data Warga</span>
                    </a>
                </li>

                <li>
                    <a href="/eventCalender" class=" waves-effect">
                        <i class="mdi mdi-calendar-text"></i>
                        <span>Kegiatan</span>
                    </a>
                </li>

                <li>
                    <a href="#" class=" waves-effect">
                        <i class="mdi mdi-account-circle-outline"></i>
                        <span>Profile</span>
                    </a>
                </li>

                <li>
                    <a href="/super-alumni-asrama" class=" waves-effect">
                        <i class="mdi mdi-library-books"></i>
                        <span>Data Alumni</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('logout') }}" onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();" class=" waves-effect">
                        <i class="mdi mdi-location-exit"></i>
                        <span>Logout</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->