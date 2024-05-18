<table class="table table-striped" id="table_subs" style="width:100%;">
  <thead>
    <tr class="text-center">
      <th>Nº</th>
      <th>Departamento</th>
      <th>Tipo</th>
      <th>Suscrito en</th>
      <th>Vence en</th>
      <th>Usado por #</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i = 1;
    foreach ($subs as $sub) :
      $valido = $sub['expires_in'] == null ? false : true;
      $valido = $valido ? date('d/m/Y', strtotime($sub['expires_in'])) : 'S/F';
      $sus_en = $sub['subscribed_in'] == null ? 'S/F' : date('d/m/Y', strtotime($sub['subscribed_in']));
    ?>
      <tr>
        <td class="text-end"><?= $i ?></td>
        <td class="text-center"><?= $sub['dep_number'] ?></td>
        <td><?= $sub['name'] ?? 'Sin Suscripción' ?></td>
        <td class="text-center"><?= $sus_en ?></td>
        <td class="text-center"><?= $valido ?></td>
        <td class="text-center"><?= $sub['cant'] ?? 0 ?></td>
      </tr>
    <?php
      $i++;
    endforeach; ?>
  </tbody>
</table>