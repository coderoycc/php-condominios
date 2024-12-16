<div class="card border-0">
  <div class="card-body">
    <div class="row">
      <div class="d-flex gap-3 justify-content-end">
        <!-- <div class="col-md-3">
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
          </div>
        </div> -->
        <div class="col-md-3 col-6">
          <div class="form-floating mb-3">
            <select class="form-select" id="type_status">
              <option value="" <?= $seen == '' ? 'selected' : '' ?>>Todos</option>
              <option value="Vistos" <?= $seen == '1' ? 'selected' : '' ?>>Vistos</option>
              <option value="No vistos" <?= $seen == '0' ? 'selected' : '' ?>>No vistos</option>
            </select>
            <label for="float">Tipo</label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="list-group">
  <?php if (count($notifications) == 0): ?>
    <p class="text-center h3 fw-semibold">Sin notificaciones</p>
  <?php endif; ?>
  <?php foreach ($notifications as $noti):
    $icon = $tags[$noti->target]['icon'] ?? $tags['other']['icon'];
    $bg = $noti->seen == 0 ? 'bg-secondary' : '';
  ?>
    <a href="#!" class="list-group-item list-group-item-action <?= $bg ?>">
      <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1"><i class="fa fa-solid <?= $icon ?>"></i> <?= $noti->event ?></h5>
        <small><?= date('d/m/Y H:i', strtotime($noti->created_at)) ?></small>
      </div>
      <p class="mb-1"><?= $noti->event_detail ?></p>
      <small class="fw-semibold"><?= $noti->condominio['name'] ?></small>
      <div class="float-end">
        <?php if ($noti->seen == 0): ?>
          <button type="button" class="btn btn-outline-info" data-bs-toggle="tooltip" data-bs-title="Marcar como visto" onclick="changeSeen(<?= $noti->id ?>)"><i class="fa fa-lg fa-solid fa-envelope"></i></button>
        <?php else: ?>
          <button type="button" class="btn btn-outline-info" data-bs-toggle="tooltip" data-bs-title="Marcar como no visto" onclick="changeSeen(<?= $noti->id ?>)"><i class="fa fa-lg fa-solid fa-envelope-open"></i></button>
        <?php endif; ?>
      </div>
    </a>
  <?php endforeach; ?>
</div>
<?php //var_dump($notifications); 
?>