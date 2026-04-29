<li class="side-menus {{ Request::is('/alumni/kegiatan') ? 'active' : '' }}">
    <a class="nav-link" href="/alumni/kegiatan">
        <i class=" fas fa-list-ol" style="color: #1ad8db;"></i><span>Kegiatan Asrama</span>
    </a>
</li>

<li class="side-menus nav-item dropdown{{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link has-dropdown" href="#">
        <i class=" fas fa-building" style="color: #9b3beb;"></i><span>Data Asrama</span>
    </a>
    <ul class="dropdown-menu">
        <li><a class="nav-link{{ Request::is('/alumni/asrama') ? 'active' : '' }}" href="/alumni/asrama">Direktur & Ketua</a></li>
        <li><a class="nav-link{{ Request::is('/alumni/warga') ? 'active' : '' }}" href="/alumni/warga">Warga</a></li>
        <li><a class="nav-link{{ Request::is('/alumni/data') ? 'active' : '' }}" href="/alumni/data">Alumni</a></li>
    </ul>
</li>

<li class="side-menus {{ Request::is('/alumni/profile/data') ? 'active' : '' }}">
    <a class="nav-link" href="/alumni/profile/data">
        <i class=" far fa-user" style="color: #31b44e;"></i><span>Profile</span>
    </a>
</li>

<li class="side-menus {{ Request::is('#') ? 'active' : '' }}">
    <a class="nav-link" href="{{ url('logout') }}" onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();">
        <i class=" fas fa-sign-out-alt" style="color: #e23e3e;"></i><span>Logout</span>
    </a>
</li>