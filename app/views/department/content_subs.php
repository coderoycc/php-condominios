<div class="row">
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr class="text-center">
          <th>ID</th>
          <th>TIPO</th>
          <th>SUSCRITO EN</th>
          <th>VENCE</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($department->subs) == 0) : ?>
          <tr>
            <td colspan="4" class="text-center">Sin suscripciones para este departamento</td>
          </tr>
        <?php else : ?>
          <?php foreach ($department->subs as $sub) : ?>
            <tr class="text-center">
              <td><?= $sub['id_subscription'] ?></td>
              <td><?= $sub['name'] ?></td>
              <td><?= date('d/m/Y', strtotime($sub['subscribed_in'])) ?></td>
              <td>
                <?= date('d/m/Y', strtotime($sub['expires_in'])) ?>
                <?= strtotime($sub['expires_in']) > strtotime(date('Y-m-d H:i:s')) ? ' <i class="fa-solid fa-circle-check text-success"></i>' : ' <i class="fa-solid fa-circle-xmark text-danger"></i>' ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>