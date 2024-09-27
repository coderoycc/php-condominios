<form id="add_form_new">
  <div class="row">
    <input type="hidden" name="paid_by" value="<?= $resident->id_user ?>" />
    <input type="hidden" name="depa_id" value="<?= $resident->department->id_department ?>" />
    <input type="hidden" name="key" value="<?= $pin ?>" />
    <div class="col-md-12 mb-2">
      <div class="form-floating">
        <select class="form-select" name="type" id="option_type_new"></select>
        <label for="type">Tipo de suscripción</label>
      </div>
    </div>
    <div class="col-md-6 mb-2 d-flex justify-content-center align-items-center">
      <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" value="6" checked>
        <label class="btn btn-outline-primary" for="btnradio1">6 Meses</label>

        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" value="12" autocomplete="off">
        <label class="btn btn-outline-primary" for="btnradio2">12 Meses</label>
      </div>
    </div>
    <div class="col-md-6 mb-2">
      <div class="form-floating">
        <input type="number" step="any" class="form-control" name="price" id="new_price_current" placeholder="00.00" value="<?= $types[1]['price'] ?>" required />
        <label for="price">Precio</label>
      </div>
    </div>
    <div class="col-md-6 mb-2">
      <div class="form-floating">
        <input type="text" class="form-control" name="razon_social" placeholder="Razon social" required />
        <label for="razon_social">Razon Social</label>
      </div>
    </div>
    <div class="col-md-6 mb-2">
      <div class="form-floating">
        <input type="text" class="form-control" placeholder="NIT o CI" name="nit" />
        <label for="nit">NIT / CI</label>
      </div>
    </div>
    <div class="col-md-6 mb-2">
      <div class="form-floating">
        <input type="text" class="form-control" value="<?= $resident->department->dep_number ?>" disabled />
        <label for="">Departamento</label>
      </div>
    </div>
    <div class="d-flex justify-content-end mt-3">
      <button type="submit" class="btn btn-success">GUARDAR</button>
    </div>
  </div>
</form>
<script>
  (async function() {
    const data_types = <?= json_encode($types) ?>;

    loadOptionsData(data_types);
    console.log(data_types)
    $(document).off('change', '#option_type_new')
    $(document).on('change', '#option_type_new', changeOptionPeriod)
    $(document).off('change', 'input[type="radio"][name="btnradio"]')
    $(document).on('change', 'input[type="radio"][name="btnradio"]', changeOptionPeriod)

    function changeOptionPeriod(e) {
      const id = $('#option_type_new').val()
      const type = data_types.find(x => x.id_subscription_type == id)
      chargePrice(type)
    }
  })();

  function chargePrice(type) {
    const period = $('input[type="radio"][name="btnradio"]:checked').val();
    if (period == 6) {
      $('#new_price_current').val(type.price)
    } else if (period == 12) {
      $('#new_price_current').val(type.annual_price)
    }
  }

  function loadOptionsData(data) {
    console.log('xdxdxd')
    let html = '';
    data.forEach(element => {
      if (element.price > 0) {
        html += `<option value="${element.id_subscription_type}">${element.name}</option>`;
      }
    });
    $('#option_type_new').html(html);
  }
</script>