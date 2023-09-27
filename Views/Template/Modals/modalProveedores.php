<!-- Modal -->
<div class="modal fade" id="modalFormProveedor" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Proveedor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

              <form id="formProveedor" name="formProveedor" class="form-horizontal">
                <input type="hidden" id="idUsuario" name="idUsuario" value="">
                <input type="hidden" id="txtIdentificacion" name="txtIdentificacion" value="">
                <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
    
                <div class="form-row">
                    <!--
                    <div class="form-group col-md-4">
                        <label for="txtIdentificacion">Identificación <span class="required">*</span> </label>
                        <input type="text" class="form-control" id="txtIdentificacion" name="txtIdentificacion" required="">
                    </div>
                    -->
                    <div class="form-group col-md-6">
                        <label for="txtNombre">Nombre(s) <span class="required">*</span> </label>
                        <input type="text" class="form-control valid validText" id="txtNombre" name="txtNombre" required="">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="txtApellido">Apellidos <span class="required">*</span> </label>
                        <input type="text" class="form-control valid validText" id="txtApellido" name="txtApellido" required="">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="txtTelefono">Teléfono <span class="required">*</span> </label>
                        <input type="text" class="form-control valid validNumber" id="txtTelefono" name="txtTelefono" required="" onkeypress="return controlTag(event);">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="txtApellido">Email <span class="required">*</span> </label>
                        <input type="email" class="form-control valid validEmail" id="txtEmail" name="txtEmail" required="">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="txtPassword">Password <span class="required"> </span> </label>
                        <input type="password" class="form-control" id="txtPassword" name="txtPassword">
                    </div>
                </div>
                
                <HR>
                <P class="text-primary">Datos Fiscales</P>
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>RFC <span class="required">*</span> </label>
                        <input type="text" class="form-control" id="txtNit" name="txtNit" required="" >  
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nombre Fiscal <span class="required">*</span> </label>
                        <input type="text" class="form-control" id="txtNombreFiscal" name="txtNombreFiscal" required="" >  
                    </div>
                    
                    <div class="form-group col-md-8">
                            <label>Calle <span class="required"></span> </label>
                            <input type="text" class="form-control" id="txtDirFiscal" name="txtDirFiscal"  >  
                        </div>
                        <div class="form-group col-md-2">
                            <label>Número exterior <span class="required"></span> </label>
                            <input type="text" class="form-control" id="txtNumExt" name="txtNumExt"  >  
                        </div>
                        <div class="form-group col-md-2">
                            <label>Número interior <span class="required"></span> </label>
                            <input type="text" class="form-control" id="txtNumInt" name="txtNumInt"  >  
                        </div>

                        <div class="form-group col-md-4">
                            <label>Colonia <span class="required"></span> </label>
                            <input type="text" class="form-control" id="txtColonia" name="txtColonia"  >  
                        </div>
                        <div class="form-group col-md-2">
                            <label>CP <span class="required"></span> </label>
                            <input type="text" class="form-control valid validCP" id="txtCP" name="txtCP"  onkeypress="return controlTag(event);">  
                        </div>

                        <div class="form-group col-md-3">
                        <label for="listEstado">Estado</label>
                            <select class="form-control" data-live-search="true" id="listEstado" name="listEstado" onchange="fntCambioCiudad();"></select>
                        </div>

                        <div class="form-group col-md-3">
                        <label for="listCiudad">Ciudad</label>
                            <select class="form-control" data-live-search="true" id="listCiudad" name="listCiudad" ></select>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="listRegimen">Regimen fiscal</label>
                            <select class="form-control" data-live-search="true" id="listRegimen" name="listRegimen" onchange="fntCambioCFDI();"></select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="listRegimen">Uso CFDI</label>
                            <select class="form-control" data-live-search="true" id="listCFDI" name="listCFDI"  ></select>
                        </div>
                </div>

               

                <div class="tile-footer">
                  <button id="btnActionForm" class="btn btn-primary" type="submit">
                        <i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;
                    <button class="btn btn-danger" type="button"  data-dismiss="modal">
                        <i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
                </div>
              </form>

      </div>
      
    </div>
  </div>
</div>




<!-- Modal -->
<div class="modal fade" id="modalViewProveedor" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Datos del Proveedor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

        <div class=="modal-body">
            <table class="table table-bordered">
                <tbody>
                    <!--
                    <tr>
                        <td>Identificación:</td>
                        <td id="cellIdentificacion"> </td>
                    </tr>
                    -->
                    <tr>
                        <td>Nombres:</td>
                        <td id="celNombre"> </td>
                    </tr>

                    <tr>
                        <td>Apellidos:</td>
                        <td id="celApellido"> </td>
                    </tr>

                    <tr>
                        <td>Teléfono:</td>
                        <td id="celTelefono"> </td>
                    </tr>

                    <tr>
                        <td>Email (Usuario):</td>
                        <td id="celEmail"> </td>
                    </tr>

                    <tr>
                        <td>RFC:</td>
                        <td id="celIde"> </td>
                    </tr>

                    <tr>
                        <td>Nombre Fiscal:</td>
                        <td id="celNomFiscal"> </td>
                    </tr>

                    <tr>
                        <td>Dirección Fiscal:</td>
                        <td id="celDirFiscal"> </td>
                    </tr>

                    <tr>
                        <td>Fecha registro:</td>
                        <td id="celFechaRegistro"> </td>
                    </tr>

                </tbody>
            </table>
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      
    </div>
  </div>
</div>

