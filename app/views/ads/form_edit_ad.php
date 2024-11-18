<form id="form_edit_ad">
  <input type="hidden" name="id" value="<?= $ad->id_ad ?>">

  <div class="row m-2">
    <p class="text-muted fw-semibold fs-4"><i class="fa-solid fa-gifts"></i> Editar datos de la publicidad</p>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="detalle_ad_edit" name="description" placeholder="descripcion" value="<?= $ad->description ?>" required>
        <label for="detalle_ad_edit">Descripción de la publicidad</label>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="url_ad_edit" name="direct_to" placeholder="url" value="<?= $ad->direct_to ?? '' ?>">
        <label for="url_ad_edit">URL de redirección (opcional)</label>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <select class="form-select" id="advertiser_ad" name="company_id" required>
          <option value="" selected>Selecciona un anunciante</option>
          <?php foreach ($advertisers as $advertiser) : ?>
            <option value="<?= $advertiser['id_company'] ?>" <?= $advertiser['id_company'] == $ad->company_id ? 'selected' : '' ?>><?= strtoupper($advertiser['company']) ?></option>
          <?php endforeach; ?>
        </select>
        <label for="advertiser_ad">Anunciante</label>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <input type="date" class="form-control" name="start_date" placeholder="date" value="<?= date('Y-m-d', strtotime($ad->start_date)) ?>">
        <label for="start_date">Fecha inicio</label>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <input type="date" class="form-control" name="end_date" placeholder="date final" value="<?= date('Y-m-d', strtotime($ad->end_date)) ?>">
        <label for="end_date">Fecha final</label>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-floating mb-3">
        <input type="text" class="form-control" disbaled readonly placeholder="Tipo de archivo" value="<?= $ad->type ?>">
        <label for="type">Tipo de archivo</label>
      </div>
    </div>
  </div>

  <!-- <div class="row m-2">
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
  </div> -->

  <div class="d-flex justify-content-end gap-2">
    <button class="btn btn-secondary" type="button" onclick="getAdsList()">Cancelar</button>
    <button class="btn btn-success" type="submit">Actualizar</button>
  </div>
</form>