<table class="table table-striped" style="width:100%;" id="table_services">
  <thead>
    <tr class="text-center">
      <th>ID SUB.</th>
      <th>Nº Dpto.</th>
      <th>Valido hasta</th>
      <th>Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($services as $service) : ?>
      <tr>
        <td><?= $service['id_subscription'] ?></td>
        <td><?= $service['dep_number'] ?></td>
        <td><?= date('d/m/Y H:i', strtotime($service['expires_in'])) ?></td>
        <td class="text-center">
          <button type="button" title="VER DETALLES" class="btn btn-info text-white" onclick="see_codes(<?= $service['id_department'] ?>)"><i class="fa-solid fa-eye"></i></button>
          <button type="button" title="LLENAR MONTOS" class="btn btn-primary text-white" onclick="fill_amounts(<?= $service['id_department'] ?>)"><i class="fa-solid fa-money-bill"></i></button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>