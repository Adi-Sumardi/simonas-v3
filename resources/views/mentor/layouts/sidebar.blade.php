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
                <li class="menu-title">Menu</li>

                <li>
                    <a href="javascript: void(0);" class="waves-effect">
                        <i class="mdi mdi-airplay"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="/mahasiswa/kegiatan" class=" waves-effect">
                        <i class="mdi mdi-calendar-text"></i>
                        <span>Kegiatan Asrama</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-library-books"></i>
                        <span>Data Asrama</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="/mahasiswa/asrama">Direktur & Ketua</a></li>
                        <li><a href="/mahasiswa/warga">Warga Asrama</a></li>
                        <li><a href="/mahasiswa/alumni">Alumni Asrama</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-calendar-check"></i>
                        <span>Daily Report</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="#">Akademik</a></li>
                        <li><a href="#">Leadership</a></li>
                        <li><a href="#">Karakter Islami</a></li>
                        <li><a href="/mahasiswa/kreatif">Kreatifitas & Kewirausahaan</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#" class=" waves-effect">
                        <i class="mdi mdi-clipboard-list-outline"></i>
                        <span>History Penilaian</span>
                    </a>
                </li>

                <li>
                    <a href="#" class=" waves-effect">
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