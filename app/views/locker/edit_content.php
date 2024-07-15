<div class="form-floating mb-3">
  <input type="text" class="form-control" value="<?= $locker->locker_number ?>" placeholder="numero" readonly disabled>
  <label for="nro_locker">N° Casillero</label>
</div>
<div class="form-floating mb-3">
  <select class="form-select" id="tipo_edit" placeholder="Detalle">
    <option value="correspondencia" <?= $locker->type == 'correspondencia' ? 'selected' : '' ?>>Solo correspondencia</option>
    <option value="todo" <?= $locker->type == 'todo' ? 'selected' : '' ?>>Todo por defecto</option>
  </select>
  <label for="detail_locker">Categoría</label>
</div>
<div class="form-floating mb-2">
  <select class="form-select" id="in_out_edit" placeholder="Entrada o salida">
    <option value="ENTRADA" <?= $locker->in_out == 'ENTRADA' ? 'selected' : '' ?>>Entrada</option>
    <option value="SALIDA" <?= $locker->in_out == 'SALIDA' ? 'selected' : '' ?>>Salida</option>
  </select>
  <label for="detail_locker">Tipo de casillero</label>
</div>