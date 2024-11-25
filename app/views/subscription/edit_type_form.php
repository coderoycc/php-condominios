<form id="edit_type_form" onsubmit="return false;">
  <input type="hidden" name="id" value="<?= $type->id ?>">
  <div class="modal-body">
    <div class="form-floating mb-3">
      <input type="text" class="form-control" name="name" placeholder="Texto etiqueta" value="<?= $type->name ?>">
      <label for="name">Nombre</label>
    </div>
    <div class="form-floating mb-3">
      <input type="text" class="form-control" name="tag" placeholder="Texto etiqueta" value="<?= $type->tag ?>">
      <label for="tag">Etiqueta</label>
    </div>
    <?php if ($type->price == 0):
      // Cuand el precio sea 0 el tiempo de duracion es editable
    ?>
      <div class="form-floating mb-3">
        <input type="number" class="form-control" value="<?= $type->months_duration ?>" name="duration" placeholder="numero" step="any" min="0">
        <label>Meses de duración (gratuito)</label>
      </div>
    <?php else: ?>
      <div class="form-floating mb-3">
        <input type="number" class="form-control" value="<?= $type->price ?>" name="price" placeholder="numero" step="any" min="0">
        <label>Precio por <?= $type->months_duration ?> mes</label>
      </div>
    <?php endif; ?>
    <!-- <div class="form-floating mb-3">
      <input type="number" class="form-control" name="annual_price" value="<?= $type->annual_price ?>" placeholder="numero" step="any" min="0">
      <label>Precio Anual</label>
    </div> -->
    <div class="form-floating mb-3">
      <input type="number" class="form-control" name="max_users" value="<?= $type->max_users ?>" placeholder="Cantidad" step="1" min="1">
      <label>Cantidad maxima de usuarios</label>
    </div>
    <div class="form-floating mb-3">
      <textarea class="form-control" placeholder="Descripcion" name="description" style="height:100px;resize:none"><?= $type->description ?></textarea>
      <label>Descripción</label>
    </div>
    <div class="d-flex justify-content-between">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="see_services" id="see_services_frm" <?= $type->see_services == 1 ? 'checked' : '' ?>>
        <label class="form-check-label" for="see_services_frm">
          Ver servicios
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="see_lockers" id="see_lockers_frm" <?= $type->see_lockers == 1 ? 'checked' : '' ?>>
        <label class="form-check-label" for="see_lockers_frm">
          Ver casilleros
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="courrier" id="courrier_frm" <?= $type->courrier == 1 ? 'checked' : '' ?>>
        <label class="form-check-label" for="courrier_frm">
          Currier
        </label>
      </div>
    </div>
  </div>
</form>