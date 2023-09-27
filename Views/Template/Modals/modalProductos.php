<!-- Modal -->
<div class="modal fade" id="modalFormProductos" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

              <form id="formProductos" name="formProductos" class="form-horizontal">
                <input type="hidden" id="idProducto" name="idProducto" value="">
                <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>

                <div class="row">
                    <div class="col-md-8">
                    <div class="row">
                        
                        <div class="form-group col-md-6">
                            <label class="control-label">Marca <span class="required">*</span></label>
                            <input class="form-control" id="txtMarca" name="txtMarca" type="text" required="">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="control-label">Nombre Producto (Referencia)<span class="required">*</span></label>
                            <input class="form-control" id="txtNombre" name="txtNombre" type="text" required="">
                        </div>
                    </div>
                        
                        <div class="form-group">
                            <label class="control-label">Descripción Producto </label>
                            <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" ></textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Código <span class="required">*</span></label>
                            <input class="form-control" id="txtCodigo" name="txtCodigo" type="text" placeholder="Código de barras" required="">
                            <br>
                            <div id="divBarCode" class="notBlock textcenter">
                                <div id="printCode">
                                    <svg id="barcode"></svg>
                                </div>
                                <button class="btn btn-success btn-sm" type="button" onClick="fntPrintBarcode('#printCode')">
                                    <i class="fas fa-print"></i> Imprimir 
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="form-group col-md-6">
                                <label class="control-label">Costo Proveedor <span class="required">*</span></label>
                                <input class="form-control" id="txtPrecio_compra" name="txtPrecio_compra" type="text" required="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Precio Venta al Publico<span class="required">*</span></label>
                                <input class="form-control" id="txtPrecio" name="txtPrecio" type="text" required="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Stock <span class="required">*</span></label>
                                <input class="form-control" id="txtStock" name="txtStock" type="text" required="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="listProveedor">Proveedor <span class="required">*</span></label><button id="btnAlta" class="btn btn-lnk text-primary" type="button" onclick="nuevoProveedor();">+ Nuevo</button>
                                <select class="form-control" data-live-search="true" id="listProveedor" name="listProveedor" required=""></select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="listCategoria">Categoría <span class="required">*</span></label>
                                <select class="form-control" data-live-search="true" id="listCategoria" name="listCategoria" required=""></select>
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
<div class="modal fade" id="modalViewProducto" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Datos de Producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

        <div class=="modal-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Codigo:</td>
                        <td id="celCodigo"></td>
                        </tr>
                        <tr>
                        <td>Marca:</td>
                        <td id="celMarca"></td>
                        </tr>
                        <tr>
                        <td>Referencia:</td>
                        <td id="celNombre"></td>
                        </tr>
                        <tr>
                        <td>Costo Proveedor:</td>
                        <td id="celPrecio_compra"></td>
                        </tr>
                        <tr>
                        <td>Precio Venta al Publico:</td>
                        <td id="celPrecio"></td>
                        </tr>
                        <tr>
                        <td>Stock:</td>
                        <td id="celStock"></td>
                        </tr>
                        <tr>
                        <td>Categoría:</td>
                        <td id="celCategoria"></td>
                        </tr>
                        <tr>
                        <td>Status:</td>
                        <td id="celStatus"></td>
                        </tr>
                        <tr>
                        <td>Descripción:</td>
                        <td id="celDescripcion"></td>
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

<!-- Modal alta proveedor-->
<div class="modal fade" id="modalAltaProv" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header headerIngreso">
        <h5 class="modal-title" id="titleModal">Alta de Proveedor</h5>
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
                <div class="form-group col-md-4">
                    <label for="txtNombre">Nombre(s) <span class="required">*</span> </label>
                    <input type="text" class="form-control valid validText" id="txtNombreP" name="txtNombreP" required="">
                </div>
                <div class="form-group col-md-4">
                    <label for="txtApellido">Apellidos <span class="required">*</span> </label>
                    <input type="text" class="form-control valid validText" id="txtApellido" name="txtApellido" required="">
                </div>
                <div class="form-group col-md-4">
                    <label for="txtTelefono">Teléfono <span class="required">*</span> </label>
                    <input type="text" class="form-control valid validNumber" id="txtTelefono" name="txtTelefono" required="" onkeypress="return controlTag(event);">
                </div>
            </div>
            
            <HR>
            <!-- <P class="text-primary">Datos Fiscales </P>  -->
            <button class="btn btn-sm btn-outline-primary" type="button" onclick="muestraRFC();">Datos fiscales y otros</button>
            <P></P>
            <div id="fiscales" class="notBlock">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>RFC <span class="required"></span> </label>
                        <input type="text" class="form-control" id="txtNit" name="txtNit"  >  
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nombre Fiscal <span class="required"></span> </label>
                        <input type="text" class="form-control" id="txtNombreFiscal" name="txtNombreFiscal"  >  
                    </div>
                    <div class="form-group col-md-6">
                        <label>Calle <span class="required"></span> </label>
                        <input type="text" class="form-control" id="txtDirFiscal" name="txtDirFiscal"  >  
                    </div>
                    <div class="form-group col-md-3">
                        <label>Número exterior <span class="required"></span> </label>
                        <input type="text" class="form-control" id="txtNumExt" name="txtNumExt"  >  
                    </div>
                    <div class="form-group col-md-3">
                        <label>Número interior <span class="required"></span> </label>
                        <input type="text" class="form-control" id="txtNumInt" name="txtNumInt"  >  
                    </div>

                    <div class="form-group col-md-4">
                        <label>Colonia <span class="required"></span> </label>
                        <input type="text" class="form-control" id="txtColonia" name="txtColonia"  >  
                    </div>
                    <div class="form-group col-md-2">
                        <label>CP <span class="required"></span> </label>
                        <input type="text" class="form-control" id="txtCP" name="txtCP"  >  
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

                    <div class="form-group col-md-4">
                    <label for="txtApellido">Email <span class="required">*</span> </label>
                    <input type="email" class="form-control valid validEmail" id="txtEmail" name="txtEmail" required="">
                </div>

                <div class="form-group col-md-4">
                    <label for="txtPassword">Password <span class="required"> </span> </label>
                    <input type="password" class="form-control" id="txtPassword" name="txtPassword">
                </div>

                </div>
                <P></P>
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
