<!-- Modal -->
<div class="modal fade" id="modalFormIngresos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva orden de Ingreso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="formIngresos" name="formIngresos" class="form-horizontal">
                <input type="hidden" id="idIngreso" name="idIngreso" value="">
                <p class="text-primary">Los campos con asterisco (<span class="required">*</span>) son obligatorios.</p>
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="listProveedor">Proveedor <span class="required">*</span></label>
                        <select class="form-control" data-live-search="true" id="listProveedor" name="listProveedor" required=""></select>
                    </div>
                    <div class="form-group col-md-2">   
                        <label class="control-label">Comprobante <span class="required">*</span></label>
                        <input class="form-control" id="txtComprobante" name="txtComprobante" type="text" required="">
                    </div>                
                    <div class="form-group col-md-2">
                        <label class="control-label">Impuesto(%) <span class="required">*</span></label>
                        <input class="form-control" id="txtImpuesto" name="txtImpuesto" value="0.0" type="text"  required="">
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">Subtotal <span class="required"> </span></label>
                        <input class="form-control" id="txtSubtotal" name="txtSubtotal" type="text" value="0.00" readonly>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">Impuesto <span class="required"> </span></label>
                        <input class="form-control" id="txtPimpuesto" name="txtPimpuesto" type="text" value="0.00" readonly>
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
                    <div class="form-group col-md-4">
                        <label for="listStatus">Estado <span class="required">*</span></label>
                        <select class="form-control selectpicker" id="listStatus" name="listStatus" required="" onchange="fntAlertaInventario();">
                            <option value="1">Activa</option>
                            <option value="2">Finalizada</option>
                            <!-- <option value="0">Cancelada</option> -->
                        </select>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <br>
                                <button id="btnActionForm" class="btn btn-primary btn-lg btn-block" type="submit">
                                    <i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span>
                                </button>
                            </div>
                            <div class="form-group col-md-6">
                                <br>
                                <button class="btn btn-danger btn-lg btn-block" type="button"  data-dismiss="modal">
                                    <i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row notBlock" id="FrmProductos">
                    <div class="form-group col-md-3">
                        <label for="listProveedor">Producto</label>
                        <select class="form-control" data-live-search="true" id="listProductos" name="listProductos"></select>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">Cantidad</label>
                        <input class="form-control" id="intCantidad" name="intCantidad" type="text">
                    </div>
                    <div class="form-group col-md-2">
                        <label class="control-label">Costo Proveedor</label>
                        <input class="form-control" id="intPrecioc" name="intPrecioc" type="text">
                    </div>
                    <!-- <div class="form-group col-md-2">
                        <label class="control-label">Etiqueta</label>
                        <input class="form-control" id="txtEtiqueta" name="txtEtiqueta" type="text">
                    </div>
                    -->
                    <div class="form-group col-md-3">
                        <br>
                        <button id="btnDetalleIngreso" class="btn btn-primary btn-lg btn-block" type="button" onclick="fntDetalleIngreso();">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Agregar Producto</span>
                        </button>
                    </div>

                    <div class="col-md-12 notBlock" id="tableProductos" >
                    
                    </div>

                          
                </div>

            </form>
            </div>    
        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="modalViewIngreso" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header header-primary">
        <h5 class="modal-title" id="titleModal">Datos de Orden de compra</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

        <div class="modal-body">
            <table class="table table-bordered" id="vistaIngreso">
                <tbody>
                    <tr>
                        <td>Comprobante:</td>
                        <td id="celComprobante"></td>
                    </tr>
                    <tr>
                        <td>Proveedor:</td>
                        <td id="celNombreProveedor"></td>
                    </tr>
                    <tr>
                        <td>Impuesto:</td>
                        <td id="celImpuesto"></td>
                    </tr>
                    <tr>
                        <td>Subtotal:</td>
                        <td id="celTotal"></td>
                    </tr>
                    <tr>
                        <td>Impuesto:</td>
                        <td id="celPimpuesto"></td>
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

        <div class="row d-print-none mt-2">
            <div class="col-12 text-right"><a class="btn btn-primary" href="javascript:window.print('#modalViewIngreso');" ><i class="fa fa-print"></i> Imprimir</a></div>
          </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      
    </div>
  </div>
</div>

