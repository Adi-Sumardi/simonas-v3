{{-- <li class="side-menus {{ Request::is('/admin/dashboard') ? 'active' : '' }}">
    <a class="nav-link" href="/admin/dashboard">
        <i class=" fas fa-desktop"></i><span>Dashboard</span>
    </a>
</li>

<li class="side-menus {{ Request::is('/admin/kegiatan') ? 'active' : '' }}">
    <a class="nav-link" href="/admin/kegiatan">
        <i class=" fas fa-list-ol"></i><span>Kegiatan Asrama</span>
    </a>
</li>

<li class="side-menus nav-item dropdown{{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link has-dropdown" href="#">
        <i class=" fas fa-building"></i><span>Data Asrama</span>
    </a>
    <ul class="dropdown-menu">
        <li><a class="nav-link{{ Request::is('/admin/asrama') ? 'active' : '' }}" href="/admin/asrama">Direktur & Ketua</a></li>
        <li><a class="nav-link{{ Request::is('/admin/warga') ? 'active' : '' }}" href="/admin/warga">Warga</a></li>
        <li><a class="nav-link{{ Request::is('/admin/alumni') ? 'active' : '' }}" href="/admin/alumni">Alumni</a></li>
    </ul>
</li>

<li class="side-menus nav-item dropdown{{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link has-dropdown" href="#">
        <i class=" fas fa-pencil-alt"></i><span>Daily Report</span>
    </a>
    <ul class="dropdown-menu">
        <li><a class="nav-link{{ Request::is('/admin/akademik') ? 'active' : '' }}" href="/admin/akademik">Akademik</a></li>
        <li><a class="nav-link{{ Request::is('/admin/leadership') ? 'active' : '' }}" href="/admin/leadership">Leadership</a></li>
        <li><a class="nav-link{{ Request::is('/admin/karakter') ? 'active' : '' }}" href="/admin/karakter">Karakter Islami</a></li>
        <li><a class="nav-link{{ Request::is('/admin/kreatif') ? 'active' : '' }}" href="/admin/kreatif">Wirausaha</a></li>
    </ul>
</li>

<li class="side-menus {{ Request::is('/admin/profile') ? 'active' : '' }}">
    <a class="nav-link" href="/admin/profile">
        <i class=" far fa-user"></i><span>Profile</span>
    </a>
</li>

<li class="side-menus {{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('logout') }}" onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
        <i class=" fas fa-sign-out-alt"></i><span>Logout</span>
    </a>
</li> --}}

<li class="side-menus {{ Request::is('/admin/dashboard') ? 'active' : '' }}">
    <a class="nav-link" href="/admin/dashboard">
        <i class=" fas fa-desktop" style="color: #4284f5;"></i><span>Dashboard</span>
    </a>
</li>

<li class="side-menus {{ Request::is('/admin/kegiatan') ? 'active' : '' }}">
    <a class="nav-link" href="/admin/kegiatan">
        <i class=" fas fa-list-ol" style="color: #1ad8db;"></i><span>Kegiatan Asrama</span>
    </a>
</li>

<li class="side-menus nav-item dropdown {{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link has-dropdown" href="#">
        <i class=" fas fa-building" style="color: #9b3beb;"></i><span>Data Asrama</span>
    </a>
    <ul class="dropdown-menu">
        <li><a class="nav-link{{ Request::is('/admin/asrama') ? 'active' : '' }}" href="/admin/asrama">Direktur & Ketua</a></li>
        <li><a class="nav-link{{ Request::is('/admin/warga') ? 'active' : '' }}" href="/admin/warga">Warga</a></li>
        <li><a class="nav-link{{ Request::is('/admin/alumni') ? 'active' : '' }}" href="/admin/alumni">Alumni</a></li>
    </ul>
</li>

<li class="side-menus nav-item dropdown{{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link has-dropdown" href="#">
        <i class=" fas fa-file-signature" style="color: #f551ef;"></i><span>Penilaian</span>
    </a>
    <ul class="dropdown-menu">
        <li><a class="nav-link{{ Request::is('/admin/penilaian/akademik') ? 'active' : '' }}" href="/admin/penilaian/akademik">Akademik</a></li>
        <li><a class="nav-link{{ Request::is('/admin/penilaian/leadership') ? 'active' : '' }}" href="/admin/penilaian/leadership">Leadership</a></li>
        <li><a class="nav-link{{ Request::is('/admin/penilaian/karakter') ? 'active' : '' }}" href="/admin/penilaian/karakter">Karakter Islami</a></li>
        <li><a class="nav-link{{ Request::is('/admin/penilaian/kreatif') ? 'active' : '' }}" href="/admin/penilaian/kreatif">Kreatifitas & Wirausaha</a></li>
    </ul>
</li>

<li class="side-menus nav-item dropdown{{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link has-dropdown" href="#">
        <i class=" fas fa-pencil-alt" style="color: #f79752;"></i><span>Daily Report</span>
    </a>
    <ul class="dropdown-menu">
        <li><a class="nav-link{{ Request::is('/admin/akademik') ? 'active' : '' }}" href="/admin/akademik">Akademik</a></li>
        <li><a class="nav-link{{ Request::is('/admin/leadership') ? 'active' : '' }}" href="/admin/leadership">Leadership</a></li>
        <li><a class="nav-link{{ Request::is('/admin/karakter') ? 'active' : '' }}" href="/admin/karakter">Karakter Islami</a></li>
        <li><a class="nav-link{{ Request::is('/admin/kreatif') ? 'active' : '' }}" href="/admin/kreatif">Kreatifitas & Wirausaha</a></li>
    </ul>
</li>

<li class="side-menus {{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link" href="/admin/history">
        <i class=" fas fa-history" style="color: #afafaf;"></i><span>History Penilaian</span>
    </a>
</li>

<li class="side-menus {{ Request::is('/admin/profile') ? 'active' : '' }}">
    <a class="nav-link" href="/admin/profile">
        <i class=" far fa-user" style="color: #31b44e;"></i><span>Profile</span>
    </a>
</li>

<li class="side-menus {{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('logout') }}" onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
        <i class=" fas fa-sign-out-alt" style="color: #e23e3e;"></i><span>Logout</span>
    </a>
</li>