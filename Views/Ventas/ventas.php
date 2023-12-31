<?php 
  headerAdmin($data); 
  getModal('modalVentas', $data);
?>

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fas fa-box"></i><?= $data['page_title']; ?>
          <?php if($_SESSION['permisosMod']['w']){ ?>
            <button class="btn btn-primary" type="button" onclick="openModal();"><i class="fas fa-plus-circle"></i> Nuevo</button>
          <?php } ?>
          </h1>
          
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url();?>ventas"><?= $data['page_title']; ?></a></li>
        </ul>
      </div>
     

      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body"> 
              <!--
              <table border="0" cellspacing="5" cellpadding="5">
              <tbody>
                <tr>
                  <td>Fecha inicial:</td>
                  <td><input type="text" id="min" name="min"></td>
                  <td>Fecha final:</td>
                  <td><input type="text" id="max" name="max"></td>
                </tr>
                </tbody></table>
                -->
              <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableVentas">
                  <thead>
                    <tr>
                      <!-- <th>Id</th> -->
                      <th>Fecha</th>
                      <th>Cliente</th>
                      <th>Comprobante</th>
                      <th>Subtotal</th>
                      <!-- <th>Impuesto</th>  -->
                      <th>Descuento</th> 
                      <th>Total</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                      
                    </tr>
                  </thead>
                  <tbody>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </main>
    <?php footerAdmin($data); ?>    