<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div class="h-100">

        <div class="user-wid text-center py-4">
            <div class="user-img">
                <img src="{{ url('/data_photo/' . Auth::user()->avatar) }}" alt="" class="avatar-md mx-auto rounded-circle">
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
                <li class="menu-title">Menu</li>

                <li>
                    <a href="/super" class="waves-effect">
                        <i class="mdi mdi-airplay"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="/super-akun" class=" waves-effect">
                        <i class="mdi mdi-account-supervisor"></i>
                        <span>Daftar Akun</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-library-books"></i>
                        <span>Kegiatan Asrama</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="/super-kegiatan-asrama">Kegiatan </a></li>
                        <li><a href="/eventCalender">Kalender</a></li>
                        <li><a href="#">Attendance</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-library-books"></i>
                        <span>Data Asrama</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="/super-direktur-asrama">Direktur & Ketua</a></li>
                        <li><a href="/super-warga-asrama">Warga Asrama</a></li>
                        <li><a href="/super-mentor-asrama">Mentor Asrama</a></li>
                        <li><a href="/super-alumni-asrama">Alumni Asrama</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-library-books"></i>
                        <span>Data Master</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="/super-pekerjaan">Pekerjaan</a></li>

                    </ul>
                </li>

                <li>
                    <a href="/super-komponen" class=" waves-effect">
                        <i class="mdi mdi-clipboard-list-outline"></i>
                        <span>Komponen</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-calendar-check"></i>
                        <span>Daily Report</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="/super-kegiatan-reporting">Reporting</a></li>
                        <li><a href="/super-kegiatan-akademik">Akademik</a></li>
                        <li><a href="/super-kegiatan-leadership">Leadership</a></li>
                        <li><a href="/super-kegiatan-karakter">Karakter Islami</a></li>
                        <li><a href="/super-kegiatan-kreatif">Kreatifitas & Kewirausahaan</a></li>
                    </ul>
                </li>

                <li>
                    <a href="/super-laporan" class=" waves-effect">
                        <i class="mdi mdi-post-outline"></i>
                        <span>Laporan</span>
                    </a>
                </li>

                <li>
                    <a href="/super-profile" class=" waves-effect">
                        <i class="mdi mdi-account-circle-outline"></i>
                        <span>Profile</span>
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