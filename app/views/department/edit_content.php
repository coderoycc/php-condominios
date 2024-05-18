<form id="form_update_department" onsubmit="return false;">
  <input type="hidden" name="id" value="<?= $department->id_department ?>">
  <div class="row">
    <div class="col-md-6 mb-2">
      <div class="form-floating">
        <input type="text" class="form-control" name="depa_num" value="<?= $department->dep_number ?>" placeholder="Nro departamento">
        <label for="add_dep_number">Nro. Departamento</label>
      </div>
    </div>
    <div class="col-md-6 mb-2">
      <div class="form-floating">
        <input type="number" class="form-control" name="bedrooms" value="<?= $department->bedrooms ?>" placeholder="Habitaciones">
        <label for="add_dep_bedrooms">Nro. de habitaciones</label>
      </div>
    </div>
    <div class="col-md-12 mb-2">
      <div class="form-floating">
        <textarea class="form-control" name="descrip" placeholder="Descripcion" style="height:100px;resize:none;"><?= $department->description ?></textarea>
        <label for="add_dep_bedrooms">Descripción</label>
      </div>
    </div>
  </div>
</form>