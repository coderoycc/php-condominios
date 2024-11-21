<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion bg-primary text-white" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="nav">
        <div class="sb-sidenav-menu-heading">CONDOMINIOS</div>
        <a class="nav-link" href="../condominiums/" data-route="condominiums/index">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
          Administración de condominios
        </a>

        <div class="sb-sidenav-menu-heading">AGRUPADO POR CONDOMINIO</div>
        <a class="nav-link" href="../users" data-route="users">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-user-shield"></i></div>
          Usuarios del Sistema
        </a>
        <a class="nav-link" href="../global/residents.php" data-route="global/residents.php">
          <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-users"></i></div>
          Residentes
        </a>
        <a class="nav-link" href="../global/subscriptions.php" data-route="global/subscriptions.php">
          <div class="sb-nav-link-icon"><i class="fa-lg fa-solid fa-wallet"></i></div>
          Suscripciones
        </a>
        <a class="nav-link" href="../services/" data-route="services">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-cash-register"></i></div>
          Administración de servicios
        </a>
        <!-- <div class="sb-sidenav-menu-heading">RESIDENTES</div>
        <a class="nav-link" href="../residents" data-route="residents">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-people-group"></i></div>
          Residentes
        </a> -->

        <div class="sb-sidenav-menu-heading">SUSCRIPCIONES</div>
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapse_subscriptions" aria-expanded="false" aria-controls="collapse_subscriptions" data-route="subscriptions">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-people-group"></i></div>
          Suscripciones
          <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="collapse_subscriptions" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
          <nav class="sb-sidenav-menu-nested nav">
            <a class="nav-link" href="../subscriptions/types.php" data-route="subscriptions/types.php">Planes suscripción</a>
            <a class="nav-link" href="../subscriptions" data-route="subscriptions/index">Departamentos con suscripción</a>
            <a class="nav-link" href="../subscriptions/reports.php" data-route="subscriptions/reports.php">Reportes</a>
          </nav>
        </div>

        <div class="sb-sidenav-menu-heading">Condominio</div>
        <!-- <a class="nav-link" href="/condominiums" data-route="condominiums">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-house-user"></i></div>
          Condominios
        </a> -->
        <a class="nav-link" href="../departments" data-route="departments">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-person-shelter"></i></div>
          Departamentos
        </a>
        <a class="nav-link" href="../lockers" data-route="lockers">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-door-closed"></i></div>
          Casilleros
        </a>

        <div class="sb-sidenav-menu-heading">Correspondencia Envíos</div>
        <a class="nav-link" href="../shipping" data-route="shipping/index" data-position="down">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-table"></i></div>
          Listado envios
        </a>
        <!-- <a class="nav-link" href="../shipping/progress.php" data-route="shipping/progress.php" data-position="down">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-box"></i></div>
          En proceso
        </a> -->

        <div class="sb-sidenav-menu-heading">MASTER</div>
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapse_master" aria-expanded="false" aria-controls="collapse_master" data-route="master">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
          Configuraciones
          <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="collapse_master" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
          <nav class="sb-sidenav-menu-nested nav">
            <a class="nav-link" href="../master/nameservices.php" data-route="master/nameservices.php" data-position="down">Nombres de servicios</a>
            <a class="nav-link" href="../master/ads.php" data-route="master/ads.php" data-position="down">Publicidad</a>
          </nav>
        </div>
        <!-- <a class="nav-link" href="../ads" data-route="ads">
          <div class="sb-nav-link-icon"><i class="fa-solid fa-film"></i></div>
          Anuncios
        </a> -->

        <!-- <div class="sb-sidenav-menu-heading">Interface</div>
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
          <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
          Layouts
          <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
          <nav class="sb-sidenav-menu-nested nav">
            <a class="nav-link" href="layout-static.html">Static Navigation</a>
            <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
          </nav>
        </div> -->
      </div>
    </div>
    <div class="sb-sidenav-footer" style="color: var(--bs-verde);">
      <div class="small">Usuario: <b><?= $user->username ?></b></div>
      <?= strtoupper($condominio->name) ?>
    </div>
  </nav>
</div>

<!-- MODAL USER -->
<div class="modal fade" id="modal_usuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= strtoupper($user->username);  ?></h5>
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