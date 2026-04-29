
<li class="side-menus {{ Request::is('/mentor/dashboard') ? 'active' : '' }}">
    <a class="nav-link" href="/mentor/dashboard">
        <i class=" fas fa-desktop" style="color: #4284f5;"></i><span>Dashboard</span>
    </a>
</li>

<li class="side-menus {{ Request::is('/mentor/kegiatan') ? 'active' : '' }}">
    <a class="nav-link" href="/mentor/kegiatan">
        <i class=" fas fa-list-ol" style="color: #1ad8db;"></i><span>Kegiatan Asrama</span>
    </a>
</li>

<li class="side-menus nav-item dropdown {{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link has-dropdown" href="#">
        <i class=" fas fa-building" style="color: #9b3beb;"></i><span>Data Asrama</span>
    </a>
    <ul class="dropdown-menu">
        <li><a class="nav-link{{ Request::is('/mentor/asrama') ? 'active' : '' }}" href="/mentor/asrama">Direktur & Ketua</a></li>
        <li><a class="nav-link{{ Request::is('/mentor/warga') ? 'active' : '' }}" href="/mentor/warga">Warga</a></li>
        <li><a class="nav-link{{ Request::is('/mentor/alumni') ? 'active' : '' }}" href="/mentor/alumni">Alumni</a></li>
    </ul>
</li>

<li class="side-menus nav-item dropdown{{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link has-dropdown" href="#">
        <i class=" fas fa-file-signature" style="color: #f551ef;"></i><span>Penilaian</span>
    </a>
    <ul class="dropdown-menu">
        <li><a class="nav-link{{ Request::is('/mentor/penilaian/akademik') ? 'active' : '' }}" href="/mentor/penilaian/akademik">Akademik</a></li>
        <li><a class="nav-link{{ Request::is('/mentor/penilaian/leadership') ? 'active' : '' }}" href="/mentor/penilaian/leadership">Leadership</a></li>
        <li><a class="nav-link{{ Request::is('/mentor/penilaian/karakter') ? 'active' : '' }}" href="/mentor/penilaian/karakter">Karakter Islami</a></li>
        <li><a class="nav-link{{ Request::is('/mentor/penilaian/kreatif') ? 'active' : '' }}" href="/mentor/penilaian/kreatif">Kreativitas & Wirausaha</a></li>
    </ul>
</li>

<li class="side-menus nav-item dropdown{{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link has-dropdown" href="#">
        <i class=" fas fa-pencil-alt" style="color: #f79752;"></i><span>Daily Report</span>
    </a>
    <ul class="dropdown-menu">
        <li><a class="nav-link{{ Request::is('/mentor/akademik') ? 'active' : '' }}" href="/mentor/akademik">Akademik</a></li>
        <li><a class="nav-link{{ Request::is('/mentor/leadership') ? 'active' : '' }}" href="/mentor/leadership">Leadership</a></li>
        <li><a class="nav-link{{ Request::is('/mentor/karakter') ? 'active' : '' }}" href="/mentor/karakter">Karakter Islami</a></li>
        <li><a class="nav-link{{ Request::is('/mentor/kreatif') ? 'active' : '' }}" href="/mentor/kreatif">Kreativitas & Wirausaha</a></li>
    </ul>
</li>

<li class="side-menus {{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link" href="/mentor/history">
        <i class=" fas fa-history" style="color: #afafaf;"></i><span>History Penilaian</span>
    </a>
</li>

<li class="side-menus {{ Request::is('/mentor/profile') ? 'active' : '' }}">
    <a class="nav-link" href="/mentor/profile">
        <i class=" far fa-user" style="color: #31b44e;"></i><span>Profile</span>
    </a>
</li>

<li class="side-menus {{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('logout') }}" onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
        <i class=" fas fa-sign-out-alt" style="color: #e23e3e;"></i><span>Logout</span>
    </a>
</li>