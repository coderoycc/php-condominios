<div class="card shadow text-center m-2">
  <div class="card-header">
    <h5 class="card-title">Detalles de la suscripción</h5>
    <button type="button" class="btn-close btn-close-content position-absolute top-0 end-0 p-3" aria-label="Close"></button>
  </div>
  <div class="card-body">
    <table class="table table-hover">
      <tbody>
        <tr>
          <th scope="row">Tipo de suscripción</th>
          <td class="text-end"><?= $subscription->type->name ?></td>
        </tr>
        <tr>
          <th scope="row">Suscrito en</th>
          <td class="text-end"><?= date('d/m/Y', strtotime($subscription->subscribed_in)) ?></td>
        </tr>
        <tr>
          <th scope="row">Expira en</th>
          <td class="text-end"><?= date('d/m/Y', strtotime($subscription->expires_in)) ?></td>
        </tr>
        <tr>
          <th scope="row">Pagado por</th>
          <td class="text-end"><?= $subscription->paid_by_name ?></td>
        </tr>
        <tr>
          <th scope="row">NIT</th>
          <td class="text-end"><?= $subscription->nit ?></td>
        </tr>
        <tr>
          <th scope="row">Código suscripción</th>
          <td class="text-end"><?= $subscription->code ?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- <div class="card-footer text-body-secondary">
    <button class="btn btn-info text-white"><i class="fa fa-fw fa-pen"></i> Cambiar</button>
    <button class="btn btn-warning"><i class="fa fa-fw fa-pause"></i> Suspender</button>
  </div> -->
</div>