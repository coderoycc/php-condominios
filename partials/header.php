<!-- SOCKET IO IMPORT -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.8.1/socket.io.min.js" integrity="sha512-8ExARjWWkIllMlNzVg7JKq9RKWPlJABQUNq6YvAjE/HobctjH/NA+bSiDMDvouBVjp4Wwnf1VP1OEv7Zgjtuxw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Style header -->
<style>
  .select_cond {
    background-color: var(--bs-success);
    border: 0;
    color: var(--bs-white);
    padding: 12px;
    outline: none;
  }

  .select_cond:active {
    border: 0;
  }

  .select_cond:focus-visible {
    border: 0;
  }

  .text-ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
  }
</style>
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-primary">
  <!-- <a class="navbar-brand ps-3" id="select_condominio" href="#!"><b><?= strtoupper($condominio->name ?? '') ?></b></a> -->
  <select class="h4 select_cond" id="select_condominio">
    <option value="" selected><?= strtoupper($condominio->name ?? '') ?></option>
    <?php foreach ($condominios as $cdm): ?>
      <option value="<?= $cdm['pin'] ?>"><?= strtoupper($cdm['name']) ?></option>
    <?php endforeach; ?>
  </select>
  <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
  <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
    <!-- <div class="input-group">
      <input class="form-control" type="text" placeholder="Buscar" aria-label="Search for..." aria-describedby="btnNavbarSearch" />
      <button class="btn btn-secondary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
    </div> -->
  </form>
  <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="width:250px">
        <li><a class="dropdown-item" href="#!">Usuario: <b><?= $user->username ?? '' ?></b></a></li>
        <li><a class="dropdown-item" href="../dash/">Ir al panel </a></li>
        <li>
          <hr class="dropdown-divider" />
        </li>
        <li class="d-grid mx-2 ">
          <a class="btn btn-info text-white" type="button" href="#!" title="Cambiar contraseña" data-bs-toggle="modal" data-bs-target="#modal_usuario_pass">
            Cambiar contraseña <i class="fa fa-lock fa-fw"></i>
          </a>
        </li>
        <li class="d-grid mx-2 mt-2">
          <a class="btn btn-danger text-white" type="button" href="#!" title="SALIR" id="idLogout">
            <i class="fa fa-sign-out-alt fa-fw "></i> Salir
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item dropdown">
      <a class="rounded-pill nav-link dropdown-toggle" id="nav_notify" href="#" role="button" data-bs-toggle="dropdown" style="background-color:var(--bs-primary);" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <span class="position-absolute start-100 translate-middle badge rounded-pill bg-info" id="q_notifications">0</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav_notify" id="list_notifications" style="width:310px; min-width:280px;">
      </ul>
    </li>
  </ul>
</nav>