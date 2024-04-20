<nav id="top_nav" class="sb-topnav navbar navbar-expand navbar-dark" style="background-color: <?= $user->color ?>;">
  <a class="navbar-brand ps-3" href="../">EMPRESA</a>
  <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
  <div class=" d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></div>
  <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-user fa-fw"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li><a class="dropdown-item" href="#!">Usuario: <?= strtoupper($user->usuario) ?></a></li>
        <!-- <li><a class="dropdown-item" href="#!">Activity Log</a></li> -->
        <!-- <li>
          <a class="dropdown-item" href="#!">
            Pendientes
            <span class="badge rounded-pill bg-success">
              +9
            </span>
          </a>
        </li> -->
        <li>
          <hr class="dropdown-divider" />
        </li>
        <li><a class="dropdown-item" href="#!" id="idLogout">Salir</a></li>
      </ul>
    </li>
  </ul>
</nav>