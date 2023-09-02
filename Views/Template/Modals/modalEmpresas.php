<!-- Modal -->
<div class="modal fade" id="modalFormEmpresa" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nueva Empresa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

              <form id="formEmpresa" name="formEmpresa" class="form-horizontal">
                <input type="hidden" id="idEmpresa" name="idEmpresa" value="">
                <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>

                <div class="row">
                    <div class="col-md-8">
                    <div class="row">
                        
                        <div class="form-group col-md-8">
                            <label class="control-label">Empresa <span class="required">*</span></label>
                            <input class="form-control" id="txtNombreEmpresa" name="txtNombreEmpresa" type="text" required="">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">RFC<span class="required">*</span></label>
                            <input class="form-control" id="txtRfcEmpresa" name="txtRfcEmpresa" type="text" required="">
                        </div>
                        <div class="form-group col-md-8">
                            <label class="control-label">Calle<span class="required">*</span></label>
                            <input class="form-control" id="txtDireccionEmpresa" name="txtDireccionEmpresa" type="text" required="">
                        </div>
                        <div class="form-group col-md-2">
                            <label>No. exterior <span class="required"></span> </label>
                            <input type="text" class="form-control" id="txtNumExt" name="txtNumExt"  >  
                        </div>
                        <div class="form-group col-md-2">
                            <label>No. interior <span class="required"></span> </label>
                            <input type="text" class="form-control" id="txtNumInt" name="txtNumInt"  >  
                        </div>

                        <div class="form-group col-md-4">
                            <label>Colonia <span class="required"></span> </label>
                            <input type="text" class="form-control" id="txtColonia" name="txtColonia"  >  
                        </div>
                        <div class="form-group col-md-2">
                            <label class="control-label">Código Postal<span class="required">*</span></label>
                            <input class="form-control valid validCP" id="txtCP" name="txtCP" type="text" required="" onkeypress="return controlTag(event);">
                        </div>
                        <!--
                        <div class="form-group col-md-4">
                            <label class="control-label">Ciudad<span class="required">*</span></label>
                            <input class="form-control" id="txtCiudadEmpresa" name="txtCiudadEmpresa" type="text" required="">
                        </div>
                        -->
                        <div class="form-group col-md-3">
                        <label for="listEstado">Estado</label>
                            <select class="form-control" data-live-search="true" id="listEstado" name="listEstado" ></select>
                        </div>

                        <div class="form-group col-md-3">
                        <label for="listCiudad">Ciudad</label>
                            <select class="form-control" data-live-search="true" id="listCiudad" name="listCiudad" ></select>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="control-label">Telefono<span class="required">*</span></label>
                            <input class="form-control" id="txtTelEmpresa" name="txtTelEmpresa" type="text" required="">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="control-label">Email<span class="required">*</span></label>
                            <input class="form-control" id="txtEmailEmpresa" name="txtEmailEmpresa" type="text" required="">
                        </div>
                    </div>
                        
                        
                    </div>

                    <div class="col-md-4">
                        

                        <div class="row">
                        <div class="form-group col-md-6">
                            <label for="listRegimen">Regimen fiscal</label>
                            <select class="form-control" data-live-search="true" id="listRegimen" name="listRegimen" ></select>
                        </div>
                            <div class="form-group col-md-6">
                                <label for="listStatus">Estado <span class="required">*</span></label>
                                <select class="form-control selectpicker" id="listStatus" name="listStatus" required="">
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <button id="btnActionForm" class="btn btn-primary btn-lg btn-block" type="submit">
                                    <i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span>
                                </button>
                            </div>
                            <div class="form-group col-md-6">
                            <button class="btn btn-danger btn-lg btn-block" type="button"  data-dismiss="modal">
                                <i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tile-footer">
                    <div class="form-group col-md-12">
                        <div id="containerGallery">
                            <span>Agregar foto (440 x 545)</span>
                            <button class="btnAddImage btn btn-info btn-sm" type="button">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <hr>
                        <div id="containerImages">
                            
                        </div>
                    </div>  
                    
                </div>

              </form>
      </div>    
    </div>
  </div>
</div>




<!-- Modal -->
<div class="modal fade" id="modalViewEmpresa" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Datos de Empresa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

        <div class=="modal-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Empresa:</td>
                        <td id="celNombreEmpresa"></td>
                        </tr>
                        <tr>
                        <td>RFC:</td>
                        <td id="celRfcEmpresa"></td>
                        </tr>
                        <tr>
                        <td>Calle y número:</td>
                        <td id="celDireccionEmpresa"></td>
                        </tr>
                        <tr>
                        <td>Colonia:</td>
                        <td id="celColoniaEmpresa"></td>
                        </tr>
                        <tr>
                        <td>Ciudad:</td>
                        <td id="celCiudadEmpresa"></td>
                        </tr>
                        <tr>
                        <td>Código Postal:</td>
                        <td id="celCpEmpresa"></td>
                        </tr>
                        <tr>
                        <td>Telefono:</td>
                        <td id="celTelEmpresa"></td>
                        </tr>
                        <tr>
                        <td>Email:</td>
                        <td id="celEmailEmpresa"></td>
                        </tr>
                        <tr>
                        <td>Status:</td>
                        <td id="celStatus"></td>
                        </tr>
                        
                        <tr>
                        <td>Fecha de registro:</td>
                        <td id="celFechaRegistro">
                        </td>
                        </tr>
                        <tr>
                        <td>Fotos de referencia:</td>
                        <td id="celFotos">
                        </td>
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

