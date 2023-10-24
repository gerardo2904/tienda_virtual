<!-- Modal -->
<div class="modal fade" id="modalFormVentas" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva orden de Salida</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="formVentas" name="formVentas" class="form-horizontal">
                <input type="hidden" id="idVenta" name="idVenta" value="">
                <input type="hidden" id="txtImpuesto" name="txtImpuesto" value="0.0">
                <input type="hidden" id="txtPimpuesto" name="txtPimpuesto" value="0.00" readonly>
                <input type="hidden" id="xcl" name="xcl" value="0">
                <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="listCliente">Cliente <span class="required">*</span></label> <button id="btnAlta" class="btn btn-lnk text-primary" type="button" onclick="nuevoCliente();">+ Agregar</button>
                        <select class="form-control" data-live-search="true" id="listCliente" name="listCliente" required="" ></select>
                    </div>
                    <!--
                    <div class="form-group col-md-1">
                        <label for="btnAlta"><span class="required"></span></label><br>
                        <button id="btnAlta" class="btn btn-lnk" type="button" onclick="nuevoCliente();">+</button>
                    </div>
                    -->
                    <div class="form-group col-md-2">   
                        <label class="control-label">Comprobante<span class="required">*</span></label>
                        <input class="form-control" id="txtComprobante" name="txtComprobante" type="text" readonly>
                    </div>                
                    <div class="form-group col-md-2">
                        <label class="control-label">Subtotal <span class="required"> </span></label>
                        <input class="form-control" id="txtSubtotal" name="txtSubtotal" type="text" value="0.00" readonly>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">Descuento <span class="required"> </span></label>
                        <input class="form-control" id="txtDescuento" name="txtDescuento" type="text" value="0.00" readonly>
                    </div>
                    
                    <div class="form-group col-md-2">
                        <label class="control-label">Total <span class="required"> </span></label>
                        <input class="form-control" id="txtTotal" name="txtTotal" type="text" value="0.00" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-8">
                        <label class="control-label">Notas </label>
                        <textarea class="form-control" id="txtNotas" name="txtNotas"></textarea>
                    </div>

                    <!- Anticipo y abonos... ->
                    <!--
                    <div class="form-group col-md-4">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Anticipo <span class="required"> </span></label>
                                <input class="form-control" id="txtAnticipo" name="txtAnticipo" type="text" value="0.00" readonly>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">Fecha Anticipo <span class="required"> </span></label>
                                <input class="form-control" id="txtFechaAnticipo" name="txtFechaAnticipo" type="text" value="0.00" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Abono 1 <span class="required"> </span></label>
                                <input class="form-control" id="txtAbono1" name="txtAbono1" type="text" value="0.00" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Fecha Abono 1 <span class="required"> </span></label>
                                <input class="form-control" id="txtFechaAbono1" name="txtFechaAbono1" type="text" value="0.00" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Abono 2 <span class="required"> </span></label>
                                <input class="form-control" id="txtAbono2" name="txtAbono2" type="text" value="0.00" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Fecha Abono 2 <span class="required"> </span></label>
                                <input class="form-control" id="txtFechaAbono2" name="txtFechaAbono2" type="text" value="0.00" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Abono 3 <span class="required"> </span></label>
                                <input class="form-control" id="txtAbono3" name="txtAbono3" type="text" value="0.00" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Fecha Abono 3 <span class="required"> </span></label>
                                <input class="form-control" id="txtFechaAbono3" name="txtFechaAbono3" type="text" value="0.00" readonly>
                            </div>
                        </div>
                    </div> -->
                    <!- Fin Anticipo y abonos... ->
                    
                     
                    <div class="form-group col-md-4">
                        <!- tabla de abonos  ->
                        <div class="row">
                        <label class="control-label">Abonos <span class="required"></span></label> <button id="btnAltaAbono" class="btn btn-lnk text-primary" type="button" onclick="nuevoAbono();">+ Agregar</button>
                            <table id="tabla_abonos" class="table table-bordered">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Abono</th>
                                    <th>Acción</th>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                
                            </table>

                        </div>
                        <!- Fin tabla de abonos  ->
                        
                        
                        <div id="lista_estado" class="notBlock">
                        <label for="listStatus">Estado <span class="required">*</span></label>
                        <select class="form-control selectpicker" id="listStatus" name="listStatus" required="" onchange="fntAlertaInventario();">
                            <option value="1">Activa</option>
                            <option value="2">Finalizada</option>
                        </select>   
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <br>
                                <button id="btnActionForm" class="btn btn-primary btn-sm btn-block" type="submit"">
                                    <i class="fa fa-fw fa-md fa-check-circle"></i><span id="btnText">Guardar</span>
                                </button>
                            </div>

                            <div class="form-group col-md-4">
                                <br>
                                <button id="btnTicket" class="btn btn-success btn-sm btn-block" type="button" onclick="impTicket();"">
                                    <i class="fa fa-fw fa-md fa-print"></i><span id="btnText">Ticket</span>
                                </button>
                            </div>

                            <div class="form-group col-md-4">
                                <br>
                                <button class="btn btn-danger btn-sm btn-block" type="button"  data-dismiss="modal">
                                    <i class="fa fa-fw fa-md fa-times-circle"></i><span>Cerrar</span>
                                </button>
                            </div>
                        </div>
                    </div>  
                </div>
                
                <div class="row notBlock" id="FrmProductos">
                    <div class="form-group col-md-3" onclick="fntObtieneStock();">
                        <label for="listProveedor">Producto</label>
                        <select class="form-control" data-live-search="true" id="listProductos" name="listProductos"  ></select>
                    </div>
                    <div class="form-group col-md-2" onclick="fntObtieneStock();">
                        <label class="control-label">Cantidad<span id="cantistock"></span></label>
                        <input class="form-control" id="intCantidad" name="intCantidad" type="text" >
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">Costo Venta</label>
                        <input class="form-control" id="intPrecio" name="intPrecio" type="text">
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">Descuento (%)</label>
                        <input class="form-control" id="intDesc" name="intDesc" value="0.0" type="text">
                    </div>
                    <!-- <div class="form-group col-md-2">
                        <label class="control-label">Etiqueta</label>
                        <input class="form-control" id="txtEtiqueta" name="txtEtiqueta" type="text">
                    </div>
                    -->
                    <div class="form-group col-md-3">
                        <br>
                        <button id="btnDetalleVenta" class="btn btn-primary btn-lg btn-block" type="button" onclick="fntDetalleVenta();">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Agregar Producto</span>
                        </button>
                    </div>

                    <div class="col-md-12 notBlock" id="tableProductos" >
                    
                    </div>
                    <div class="form-group col-md-10">
                    </div>
                    <div class="col-md-2">
                        <button id="btnFinaliza" class="btn btn-danger btn-block" type="button" onclick="finaliza();">
                            <i class="fa fa-fw fa-lg fa-duotone fa-flag-checkered"></i><span id="btnText">Terminar venta</span>
                        </button>
                    </div>
                </div>

            </form>
            </div>    
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="modalViewVenta" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Datos de Orden de salida</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

        <div class="modal-body">
            <table class="table table-bordered" id="vistaVenta">
                <tbody>
                    <tr>
                        <td>Comprobante:</td>
                        <td id="celComprobante"></td>
                    </tr>
                    <tr>
                        <td>Cliente:</td>
                        <td id="celNombreCliente"></td>
                    </tr>
                   <!-- <tr>
                        <td>Impuesto:</td>
                        <td id="celImpuesto"></td>
                    </tr>
                    -->
                    <tr>
                        <td>Subtotal:</td>
                        <td id="celTotal"></td>
                    </tr>
                    
                    <tr>
                        <td>Descuento:</td>
                        <td id="celDescuento"></td>
                    </tr>
                    
                    <tr>
                        <td>Total:</td>
                        <td id="celGrantotal"></td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td id="celStatus"></td>
                    </tr>
                    <tr>
                        <td>Notas:</td>
                        <td id="celNotas"></td>
                    </tr>
                    <tr>
                        <td>Fecha de registro:</td>
                        <td id="celFechaRegistro"></td>
                    </tr>
                    
                </tbody>
            </table>
            
                <div id="celProds"></div>  
            
        </div>
        <div class="modal-footer">
        
            <!-- <div class="col-12 text-right"><a class="btn btn-primary" href="javascript:window.print('#modalViewVenta');" ><i class="fa fa-print"></i> Imprimir </a></div> -->
            <!-- <div class="col-12 text-right"><a class="btn btn-primary" onclick="fntImprimeRecibo();" ><i class="fa fa-print"></i><span id="btnText">Imprimir </span></a></div> -->
            <!-- <div class="col-12 text-right"><button type="button" class="btn btn-primary" onclick="fntImprimeRecibo();"><i class="fa fa-print"></i> Imprimir</button></div> -->
                
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalAltaCliente" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header headerIngreso">
        <h5 class="modal-title" id="titleModal">Alta de  Cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
            <form id="formCliente" name="formCliente" class="form-horizontal">
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

                <div class="form-group col-md-4">
                    <label for="txtNombre">Nombre(s) <span class="required">*</span> </label>
                    <input type="text" class="form-control valid validText" id="txtNombre" name="txtNombre" required="">
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


<!-- Modal abonos-->
<div class="modal fade" id="modalAltaAbono" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header headerIngreso">
        <h5 class="modal-title" id="titleModal">Nuevo abono</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
            <form id="formAbono" name="formAbono" class="form-horizontal">
            <input type="hidden" id="idVentaA" name="idVentaA" value="">
            <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="txtFecha">Fecha <span class="required">*</span> </label>
                    <div class="dflex">
                        <input type="text" class="form-control date-picker"  id="txtFecha" name="txtFecha" required="" value="<?php echo date("d/m/Y");?>">
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="txtAbono">Abono <span class="required">*</span> </label>
                    <input type="text" class="form-control valid validNumber" id="txtAbono" name="txtAbono" required="" onkeypress="return controlTag(event);">
                </div>
                
            </div>
            
            <HR>
           

            <div class="tile-footer">
                <button id="btnActionFormA" class="btn btn-primary" type="submit">
                    <i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;
                <button class="btn btn-danger" type="button"  data-dismiss="modal">
                    <i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
            </div>
            </form>
        </div>  
    </div>
  </div>
</div>