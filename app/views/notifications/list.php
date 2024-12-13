<div class="list-group">
  <?php if (count($notifications) == 0): ?>
    <p class="text-center h3 fw-semibold">No tiene notificaciones aún</p>
  <?php endif; ?>
  <?php foreach ($notifications as $noti):
    $icon = $tags[$noti->target]['icon'] ?? $tags['other']['icon'];
  ?>
    <a href="#!" class="list-group-item list-group-item-action">
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