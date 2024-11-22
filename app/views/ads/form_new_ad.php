<form id="form_new_ad">
  <div class="row m-2">
    <p class="text-muted fw-semibold fs-4"><i class="fa-solid fa-gifts"></i> Agregar nueva publicidad</p>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="detalle_ad" name="description" placeholder="descripcion" required>
        <label for="detalle_ad">Descripción de la publicidad</label>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="url_ad" name="direct_to" placeholder="url">
        <label for="url_ad">URL de redirección (opcional)</label>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <select class="form-select" id="advertiser_ad" name="company_id" required>
          <option value="" selected>Selecciona un anunciante</option>
          <?php foreach ($advertisers as $advertiser) : ?>
            <option value="<?= $advertiser['id_company'] ?>"><?= strtoupper($advertiser['company']) ?></option>
          <?php endforeach; ?>
        </select>
        <label for="advertiser_ad">Anunciante</label>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <input type="date" class="form-control" name="start_date" placeholder="date" value="<?= date('Y-m-d') ?>">
        <label for="start_date">Fecha inicio</label>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <input type="date" class="form-control" name="end_date" placeholder="date final" value="<?= date('Y-m-d') ?>">
        <label for="end_date">Fecha final</label>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <select name="target" class="form-select">
          <option value="O">TODOS</option>
          <option value="F">FEMENINO</option>
          <option value="M">MASCULINO</option>
        </select>
        <label for="target">Público objetivo</label>
      </div>
    </div>
  </div>

  <div class="row m-2">
    <p class="text-muted fw-semibold">Contenido para mostrar</p>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <select class="form-select" id="type_ad" name="type" required>
          <option value="" selected>Selecciona un tipo</option>
          <option value="IMAGEN">Imagen</option>
          <option value="VIDEO">Video</option>
          <option value="GIF">GIF</option>
        </select>
        <label for="type">Tipo de anuncio</label>
      </div>
    </div>
    <div class="col-md-8" id="add_content"></div>
  </div>
  <div class="d-flex justify-content-end">
    <button class="btn btn-success" type="submit">GUARDAR</button>
  </div>
</form>