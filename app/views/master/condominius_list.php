<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">NOMBRE</th>
        <th scope="col">PIN</th>
        <th scope="col">DIRECCIÓN</th>
        <th scope="col">CIUDAD</th>
        <th scope="col">PAGOS QR</th>
        <th scope="col">ACCIONES</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($condominios as $condominio): ?>
        <tr>
          <td class="text-center"><?= $condominio['idCondominio'] ?></td>
          <td class="text-start"><?= strtoupper($condominio['name']) ?></td>
          <td class="text-center"><?= $condominio['pin'] ?></td>
          <td class="text-center"><?= $condominio['address'] ?></td>
          <td class="text-center"><?= $condominio['city'] ?></td>
          <td class="text-center"><?= $condominio['enable_qr'] == '0' ? 'NO' : 'SI' ?></td>
          <td class="text-center">
            <!-- <div class="btn-group" role="group" aria-label="Basic example">
              <button type="button" data-bs-toogle="modal" data-bs-target="#editar_condominio" class="btn btn-info">Editar</button>
            </div> -->
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>