<div class="card shadow text-center m-2">
  <div class="card-header">
    <h5 class="card-title">Historial de suscripciones</h5>
    <button type="button" class="btn-close btn-close-content position-absolute top-0 end-0 p-3" aria-label="Close"></button>
  </div>
  <div class="card-body">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">Tipo suscripción</th>
          <th scope="col">Suscrito en</th>
          <th scope="col">Fin suscripción</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($subscriptions) == 0): ?>
          <tr>
            <td colspan="3">Este departamento no tiene suscripciones</td>
          </tr>
        <?php else: ?>
          <?php foreach ($subscriptions as $subscription): ?>
            <tr>
              <td><?= strtoupper($subscription['type']) ?></td>
              <td><?= date('d/m/Y', strtotime($subscription['subscribed_in'])) ?></td>
              <td><?= date('d/m/Y', strtotime($subscription['expires_in'])) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>