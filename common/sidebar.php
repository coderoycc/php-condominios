<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion" style="background-color:<?= $user->color ?>;">
    <div class="sb-sidenav-menu">
      <div class="nav text-white">
        <!-- <div class="sb-sidenav-menu-heading">Core</div>
        <a class="nav-link" href="../dash/">
          <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
          MENU INICIO
        </a> -->
        <div class="sb-sidenav-menu-heading">ACCIONES</div>
        <?php if ($user->rol != 'ALMACEN') : ?>
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#enviosCollapse" aria-expanded="false" aria-controls="enviosCollapse">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-share-from-square"></i></div> ENVIOS
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="enviosCollapse" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="../envios/nuevo.php">Nuevo envio</a>
              <a class="nav-link" href="../envios/">Lista envios</a>
            </nav>
          </div>
          <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#recepcionCollapse" aria-expanded="false" aria-controls="recepcionCollapse">
            <div class="sb-nav-link-icon"><i class="fa fa-solid fa-dolly"></i></div> RECEPCIÓN
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
          </a>
          <div class="collapse" id="recepcionCollapse" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
            <nav class="sb-sidenav-menu-nested nav">
              <a class="nav-link" href="../recepcion/">Lista recepción</a>
              <a class="nav-link" href="../recepcion/entregados.php">Entregados</a>
            </nav>
          </div>
        <?php else : ?>
          <a class="nav-link" href="../recepcion/almacen.php" data-bs-target="#recepcionCollapse">
            <div class="sb-nav-link-icon"><i class="fa fa-solid fa-dolly"></i></div>
            Almacen
          </a>
        <?php endif; ?>

        <div class="sb-sidenav-menu-heading">OPCIONES</div>
        <?php if ($user->rol == 'ADMIN') : ?>
          <a class="nav-link" href="../usuarios/" data-bs-target="#usuariosCollapse">
            <div class="sb-nav-link-icon"><i class="fa fa-user-plus"></i></div>
            Administrar usuarios
          </a>
        <?php endif; ?>
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#configModals" aria-expanded="false" aria-controls="configModals">
          <div class="sb-nav-link-icon"><i class="fa fa-solid fa-gear"></i></div> Configuraciones
          <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="configModals" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
          <nav class="sb-sidenav-menu-nested nav">
            <a class="nav-link" href="#" type="button" data-bs-toggle="modal" data-bs-target="#modal_usuario" data-id="<?= $user->idUsuario ?>">Cambiar Contraseña</a>
            <a class="nav-link" href="#" type="button" data-bs-toggle="modal" data-bs-target="#modal_cambiar_color" data-id="<?= $user->idUsuario ?>">Cambiar color del menú</a>
          </nav>
        </div>
      </div>
    </div>
    <div class="sb-sidenav-footer text-white" style="background-color:<?= $user->color ?>;">
      <div id="bg-transparent"></div>
      <div class="small text-truncate">Identificado como:</div>
      <?= strtoupper($user->usuario) ?>
    </div>
  </nav>
</div>

<!-- MODAL USER -->
<div class="modal fade" id="modal_usuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= strtoupper($user->usuario);  ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 align="center">¿Cambiar contraseña?</h5>
        <div class="dropdown-divider"></div>
        <form>
          <input type="hidden" value="" id="id_user">
          <div class="form-group">
            <label for="pass" class="col-form-label">Contraseña actual:</label>
            <div class="input-group mb-3">
              <input type="password" class="form-control" aria-label="Recipient's username" aria-describedby="pass-addon" id="pass">
              <div class="input-group-append">
                <span class="input-group-text" id="pass-addon" data-visible="false" data-obj="pass" style="cursor:pointer;" onclick="showPass(this)"><i class="fas fa-eye"></i></span>
              </div>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <div class="form-group">
            <label for="n_pass" class="col-form-label">Nueva Contraseña:</label>
            <div class="input-group mb-3">
              <input type="password" class="form-control" id="n_pass" aria-describedby="n_pass-addon">
              <div class="input-group-append">
                <span class="input-group-text" id="n_pass-addon" data-visible="false" data-obj="n_pass" style="cursor:pointer;" onclick="showPass(this)"><i class="fas fa-eye"></i></span>
              </div>
            </div>
          </div>
          <div class="form-group" style="margin-top:-5px;">
            <label for="pass_repeat" class="col-form-label">Repita su nueva contraseña:</label>
            <input type="password" class="form-control" id="pass_repeat">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
        <button type="button" class="btn btn-primary" id="btn_cambiar" data-bs-dismiss="modal" onclick="cambiarPass()">CAMBIAR</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL CAMBIAR COLOR -->
<div class="modal fade" id="modal_cambiar_color" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <h5 align="center">Elija un color para el menú</h5>
        <input type="hidden" value="" id="id_user_color">
        <div class="d-flex justify-content-center">
          <input type="color" id="color_menu" value="#212529">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
        <button type="button" class="btn btn-primary" id="btn_cambiar" data-bs-dismiss="modal" onclick="cambiarColor()">TERMINAR</button>
      </div>
    </div>
  </div>
</div>