<div class="row m-2">
  <p class="text-muted fw-semibold fs-4"><i class="fa-solid fa-gifts"></i> Agregar nueva publicidad</p>
  <div class="col-md-6">
    <div class="form-floating mb-3">
      <input type="text" class="form-control" id="detalle_ad" placeholder="descripcion">
      <label for="detalle_ad">Descripción de la publicidad</label>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-floating mb-3">
      <input type="text" class="form-control" id="url_ad" placeholder="url">
      <label for="url_ad">URL de redirección (opcional)</label>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-floating mb-3">
      <select class="form-select" id="advertiser_ad">
        <option selected>Selecciona un anunciante</option>
        <?php foreach ($advertisers as $advertiser) : ?>
          <option value="<?= $advertiser['id_advertiser'] ?>"><?= strtoupper($advertiser['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <label for="advertiser_ad">Anunciante</label>
    </div>
  </div>
</div>