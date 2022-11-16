<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow" style="background-color: #f44336  !important;">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0 text-center" href="#" style="width:relative; font-size: 0.9rem;">
        Bach Khoa E-Learning
    </a>

    <ul class="navbar-nav px-3 ml-auto">
        <li class="nav-item  dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php $auth_user->getUsername(); ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="profile">Profile</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="../logout?logout=true">Log Out</a>
            </div>
        </li>
    </ul>
</nav>