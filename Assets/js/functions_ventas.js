let tableVentas;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

$(document).on('focusin', function(e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});



document.addEventListener('DOMContentLoaded', function(){

    tableVentas = $('#tableVentas').dataTable( {
        "aProcessing:":true,
        "aServerSide":true,
        "language":{
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " "+base_url+"Ventas/getVentas",
            "dataSrc":""
        },
        "columns":[
            
            {"data":"nombre_cliente"},
            {"data":"comprobante"},
            {"data":"total"},
          //  {"data":"pimpuesto"}, 
            {"data":"sdescuento"},
            {"data":"grantotal"},
            {"data":"status"},
            {"data":"options"}
        ],
        'dom': 'lBfrtip',
        'buttons': [
            {
                "extend": "copyHtml5",
                "text": "<i class='fas fa-copy'></i> Copiar",
                "titleAttr": "Copiar",
                "className": "btn btn-secondary"
            },
            {
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel'></i> Excel",
                "titleAttr": "Exportar a Excel",
                "className": "btn btn-success"
            },
            {
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf'></i> PDF",
                "titleAttr": "Exportar a PDF",
                "className": "btn btn-danger"
            },
            {
                "extend": "csvHtml5",
                "text": "<i class='fas fas-file-copy'></i> CSV",
                "titleAttr": "Exportar a CSV",
                "className": "btn btn-info"
            }
        ],
        "resonsieve":"true",
        "bDestroy":true,
        "iDisplayLength":10,
        "order":[[0,"desc"]]
    });


    tinymce.init({
        selector: '#txtNotas',
        width: "100%",
        height: 200,    
        statubar: true,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table directionality emoticons template paste "
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
    });

    
     
},false);


window.addEventListener('load', function(){

    //let num=0;
    /*const opcionCambiada = () => {
        num+=1;
        console.log("Cambio "+num);
      };
    */
   const opcionCambiada = () => {
        
                $('#listCliente').selectpicker('render');      
           
        
        //console.log("cliente: "+document.formVentas.querySelector('#listCliente').innerHTML);
   };

      $select=document.querySelector("#listCliente");
      $select.addEventListener("change", opcionCambiada);
      
   // Si se activa un modal  fntClientes2();

   $('#modalAltaCliente').on('hidden.bs.modal', function (event) {
    //alert("si");
    fntClientes2();
    
  });

  
       
    if(document.querySelector("#formVentas")){
        let formVentas = document.querySelector('#formVentas');
        formVentas.onsubmit = function(e){
            e.preventDefault();
            
            let intIdCliente = document.querySelector("#listCliente").selectedOptions[0].text;
            let strComprobante = document.querySelector('#txtComprobante').value;
            let strImpuesto = document.querySelector('#txtImpuesto').value;
            //let strDescuento = document.querySelector('#txtDescuento').value;

            let status = document.querySelector('#listStatus').value;

            switch (status) {
                case "1":
                  status = '<span class="badge badge-success">Activa</span>';
                  break;
                case "2":
                    status = '<span class="badge badge-info">Finalizada</span>';
                  break;
                case "0":
                    status = '<span class="badge badge-danger">Cancelada</span>';
                  break;
              }


            let pimp = parseFloat(document.querySelector('#txtPimpuesto').value).toFixed(2);
            let sub = parseFloat(document.querySelector('#txtSubtotal').value).toFixed(2);
            let gtotal = parseFloat(document.querySelector('#txtTotal').value).toFixed(2);
            let sDesc =  parseFloat(document.querySelector('#txtDescuento').value).toFixed(2);
            
            
            //console.log(document.querySelector('#txtNotas').value);

            if(intIdCliente == '' || strComprobante == '' || strImpuesto == ''){
                swal("Atención","Todos los campos son obligatorios.","error");
                return false;
            }

            let elementsValid = document.getElementsByClassName("valid");
            for (let i = 0 ; i < elementsValid.length; i++){
                if (elementsValid[i].classList.contains('is-invalid')){
                    swal("Atención", "Por favor verifique los campos en rojo.", "error");
                    return false;
                }
            }

            //if(document.querySelector('#listStatus').value == 2){
            //    swal("Aplicar","Si","success");
            //}

            divLoading.style.display = "flex";
            let SMONEY = "MXN "; //Simbolo de moneda.
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'Ventas/setVenta';
            let formData = new FormData(formVentas);
            request.open('POST',ajaxUrl, true);
            request.send(formData);

            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        
                        //swal("",objData.msg,"success");
                        //document.querySelector('#idIngreso').value = objData.data.idingreso;
                        //document.querySelector('#FrmProductos').classList.remove("notBlock");
                        //document.querySelector('#tableProductos').classList.remove("notBlock");

                        if(rowTable == ""){
                            // Si es una venta  nuevo, refresca la tabla y manda a la primera pagina
                            tableVentas.api().ajax.reload();    
                        }else{
                            let SMONEY = "MXN "; //Simbolo de moneda.
                            // Si es edicion, se mantiene en la pagina actual y solo modifica el renglon.
                            rowTable.cells[0].textContent = intIdCliente;
                            rowTable.cells[1].textContent = strComprobante;
                            rowTable.cells[2].textContent = SMONEY+sub;
                            //rowTable.cells[3].textContent = SMONEY+pimp;
                            rowTable.cells[3].textContent = SMONEY+sDesc;
                            rowTable.cells[4].textContent = SMONEY+gtotal;
                            rowTable.cells[5].innerHTML = status;
                            rowTable = "";
                            
                        }
                        //$('#modalFormVentas').modal("hide");
                        //formVentas.reset();
                        swal("Ordenes de salida", objData.msg, "success");
                        
                        document.querySelector("#idVenta").value=objData.idventa;

                        //document.querySelector("#idVenta").value;
                        //document.querySelector("#txtImpuesto").value;
                        
                        
                        //*********************************************** 
                          // Esta variable rowTable, tome todo el valor de la fila de la tabla.  
                        //rowTable = element.parentNode.parentNode.parentNode;
                        //rowTable.cells[1].textContent
                        //console.log(rowTable);  
                        document.querySelector('#titleModal').innerHTML = "Actualizar Orden de Salida";
                        document.querySelector('.modal-header').classList.replace("headerRegister","headerUpdate");
                        document.querySelector('#btnActionForm').classList.replace("btn-primary","btn-info");
                        document.querySelector('#btnText').innerHTML= "Actualizar";
                        
                        let SMONEY = "MXN "; //Simbolo de moneda.
                        let idventa = document.querySelector("#idVenta").value;
                        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                        let ajaxUrl = base_url+'Ventas/getVenta/'+idventa;
                        request.open("GET",ajaxUrl,true);
                        request.send();

                        request.onreadystatechange = function(){

                            if(request.readyState == 4 && request.status == 200){
                                let objData = JSON.parse(request.responseText);
                                //console.log(objData);
                                if(objData.status){
                                    
                                    document.querySelector('#FrmProductos').classList.remove("notBlock");
                                    document.querySelector('#tableProductos').classList.remove("notBlock");
                                    
                                    document.querySelector('#idVenta').value = objData.data.idventa;
                                    document.querySelector('#listCliente').value = objData.data.idcliente;
                                    document.querySelector('#txtComprobante').value = objData.data.comprobante;
                                    document.querySelector('#txtImpuesto').value = objData.data.impuesto;
                                    document.querySelector('#txtSubtotal').value = objData.data.total;
                                    document.querySelector('#txtPimpuesto').value = objData.data.pimpuesto;
                                    //document.querySelector('#txtDescuento').value = objData.data.descuento;
                                    document.querySelector('#txtTotal').value = objData.data.grantotal;
                                    document.querySelector('#txtNotas').value = objData.data.notas;
                                    tinymce.activeEditor.setContent(objData.data.notas); 
                                    document.querySelector("#listStatus").value = objData.data.status;
                                    $('#listCliente').selectpicker('render');
                                    $('#listStatus').selectpicker('render');
                    
                                    let arreglo = objData.data.productos;
                    
                                    
                                    //console.log(arreglo);
                                    /*
                                    $('#tableProductos').DataTable({
                                        ajax: arreglo,
                                        columns: [
                                            { data: 'nombre' },
                                            { data: 'cantidad' },
                                            { data: 'precioc' },
                                            { data: 'etiqueta' },
                                        ],
                                    });
                                */
                                    //let htmlProds = '<table id="tablita" class="table table-bordered"><tbody><th style="text-align: center">Acción</th><th style="text-align: center">Producto</th><th style="text-align: center">Imagen</th><th style="text-align: center">Cantidad</th><th style="text-align: center">Precio Unitario</th><th style="text-align: center">Total</th>   ';
                                    let htmlProds = '<table id="tablita" class="table table-bordered"><tbody><th class="col-md-1" style="text-align: center">Acción</th><th class="col-md-2" style="text-align: center">Producto</th><th class="col-md-1" style="text-align: center">Imagen</th><th class="col-md-1" style="text-align: center">Cantidad</th><th class="col-md-2" style="text-align: center">Costo</th><th class="col-md-1" style="text-align: center">% desc</th><th class="col-md-2" style="text-align: center">Descuento</th><th class="col-md-2" style="text-align: center">Total</th>   ';
                                    let suma = parseFloat(0.0);
                                    let imp = parseFloat(0.0);
                                    let gtotal = parseFloat(0.0);
                                    let desc       = parseFloat(0.0);
                                    let gdescuento = parseFloat(0.0);
                                    let pdesc = 0.0;
                                    let pdesc2=0.0;
                    
                                    let idDetalle = 0;
                                    if(objData.data.productos.length > 0){
                                        let objProds = objData.data.productos;
                                        for (let p = 0; p < objProds.length; p++) {
                    
                                            preciov_tempo = numeral(objProds[p].precio);
                                            preciov_tempo = SMONEY+preciov_tempo.format('0,0.00');
                                            
                                            if(objProds[p].descuento>1){
                                                pdesc=objProds[p].descuento/100;
                                                pdesc2=objProds[p].descuento;
                                            }else{
                                                pdesc=objProds[p].descuento;
                                                pdesc2=objProds[p].descuento*100;
                                            }

                                            descuentov_tempo = numeral((objProds[p].precio*objProds[p].cantidad)*pdesc);
                                            descuentov_tempo = SMONEY+descuentov_tempo.format('0,0.00');
                    
                                            total_tempo = numeral((objProds[p].precio*objProds[p].cantidad)-((objProds[p].precio*objProds[p].cantidad)*pdesc));
                                            total_tempo = SMONEY+total_tempo.format('0,0.00');

                    
                    
                                            let idDetalle = objProds[p].iddetalle_venta;
                                            htmlProds +=`<tr class="odd" role="row">
                                                                <td><div class="text-center"><button class="btn btn-danger btn-sm" type="button" onClick="fntBorrarDetalleVenta(${idDetalle});"><i class="far fa-trash-alt"></i></button></div></td>
                                                                <td>${objProds[p].marca} ${objProds[p].nombre}</td>
                                                                <td><img src="${base_url}Assets/images/uploads/${objProds[p].img}" width="72" height="52"></td>
                                                                <td id="canti${p}" align="right">${objProds[p].cantidad}</td>
                                                                <td id="precio${p}" align="right">${preciov_tempo}</td>
                                                                <td class="sm-1"><input  id="txtPorciento${p}" name="txtPorciento${p}" type="text" value=${pdesc2} onChange="cambia2('txtPorciento${p}', 'precio${p}', 'descu${p}', 'total${p}', 'canti${p}', ${idDetalle});"></td>
                                                                <td id="descu${p}" align="right">${descuentov_tempo}</td>
                                                                <td id="total${p}" align="right">${total_tempo}</td>
                                                            </tr>`;
                                            
                                            desc       = parseFloat((objProds[p].precio*objProds[p].cantidad)*pdesc);
                                            suma       = parseFloat(suma) + parseFloat((objProds[p].precio*objProds[p].cantidad)-desc);
                                            gdescuento = parseFloat(gdescuento)+desc;
                                        }
                                    }
                                    impp = document.querySelector('#txtImpuesto').value;
                                    imp = parseFloat(suma) * impp;
                                    gtotal = suma + imp;
                    
                                    suma_tempo       = numeral(suma);
                                    imp_tempo        = numeral(imp);
                                    gtotal_tempo     = numeral(gtotal);
                                    gdescuento_tempo = numeral(gdescuento);
                    
                                    suma_tempo       = SMONEY+suma_tempo.format('0,0.00');
                                    imp_tempo        = SMONEY+imp_tempo.format('0,0.00');
                                    gtotal_tempo     = SMONEY+gtotal_tempo.format('0,0.00');
                                    gdescuento_tempo = SMONEY+gdescuento_tempo.format('0,0.00');
                    
                    
                    
                                    //htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td></td><td></td><td align="right">Subtotal</td><td align="right">'+suma_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Impuesto</td><td align="right">'+imp_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Descuento</td><td align="right">'+gdescuento_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Total</td><td align="right">'+gtotal_tempo+'</td></tr></tbody></table>';
                                    htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td></td><td></td><td></td><td align="right">Total</td><td id="gtotal" align="right">'+gtotal_tempo+'</td></tr></tbody></table>';
                                    document.querySelector("#tableProductos").innerHTML = htmlProds;
                                    //onClick="fntDelInfo('.$arrData[$i]['idingreso'].')" title="Cancelar orden de compra"
                                    actualiza_impuesto(arreglo,objData.data.impuesto);
                                    
                                }
                            }
                            $('#modalFormVentas').modal('show');
                        }
                        

                        //*********************************************** 

                        //console.log("venta="+document.querySelector("#idVenta").value+" Comprobante: "+document.querySelector("#txtComprobante").value);
                        
                        
                    }else{
                        swal("Error", objData.msg, "error");
                    }
                }
                divLoading.style.display = "none";

                return false;
            }

        }
    }

    // Procesar si hay un cliente nuevo...
    if(document.querySelector("#formCliente")){
        let formCliente = document.querySelector('#formCliente');
        formCliente.onsubmit = function(e){
            e.preventDefault();
            let strIdentificacion = document.querySelector('#txtIdentificacion').value;
            let strNombre = document.querySelector('#txtNombre').value;
            let strApellido = document.querySelector('#txtApellido').value;
            let strEmail = document.querySelector('#txtEmail').value;
            let intTelefono = document.querySelector('#txtTelefono').value;
            let strNit = document.querySelector('#txtNit').value;
            let strNombreFiscal = document.querySelector('#txtNombreFiscal').value;
            let strDirFiscal = document.querySelector('#txtDirFiscal').value;

            let strPassword = document.querySelector('#txtPassword').value;
            

            if(/*strIdentificacion == '' || */ strNombre == '' || strApellido == '' ||  strEmail == '' || intTelefono == '' /*|| strNit == '' || strNombreFiscal == '' || strDirFiscal == '' */){
                swal("Atención","Todos los campos son obligatorios.","error");
                return false;
            }

            let elementsValid = document.getElementsByClassName("valid");
            for (let i = 0 ; i < elementsValid.length; i++){
                if (elementsValid[i].classList.contains('is-invalid')){
                    swal("Atención", "Por favor verifique los campos en rojo.", "error");
                    return false;
                }
            }

            divLoading.style.display = "flex";

            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'Clientes/setCliente';
            let formData = new FormData(formCliente);
            request.open('POST',ajaxUrl, true);
            request.send(formData);

            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                       /* 
                        const $select = document.querySelector("#listCliente");

                        for (let i = $select.options.length; i >= 0; i--) {
                            $select.remove(i);
                          }
                          */
                        
                        //location.reload();

        

                        
                        $('#modalAltaCliente').modal("hide");
                        formCliente.reset();
                        //swal("Clientes", objData.msg, "success");
                    }else{
                        swal("Error", objData.msg, "error");
                    }
                }
                divLoading.style.display = "none";
                
                return false;
            }

        }
    }
    // Fin de procesamiento de cliente nuevo...

    // Agregar un abono...
    if(document.querySelector("#formAbono")){
        let formAbono = document.querySelector('#formAbono');
        formAbono.onsubmit = function(e){
            e.preventDefault();

            document.formAbono.querySelector('#idVentaA').value = document.querySelector('#idVenta').value;
            let idVenta = document.querySelector('#idVentaA').value;
                
            let fecha = new Date().toLocaleDateString();
            let abono = 0;

            //alert("idventa= "+idVenta+" fecha= "+fecha+" abono= "+abono);
                //document.formAbono.querySelector('#txtFecha').value = fecha;
                //document.formAbono.querySelector('#txtAbono').value = abono;
            //document.querySelector('#txtFecha').value = fecha;
            //document.querySelector('#txtAbono').value = abono;

            fecha = document.formAbono.querySelector('#txtFecha').value;
            abono = document.formAbono.querySelector('#txtAbono').value;

            //alert("idventa= "+idVenta+" fecha= "+fecha+" abono= "+abono);

            if(idVenta == '' || fecha == '' ||  abono == '' ){
                swal("Atención","Todos los campos son obligatorios.","error");
                return false;
            }

            let elementsValid = document.getElementsByClassName("valid");
            for (let i = 0 ; i < elementsValid.length; i++){
                if (elementsValid[i].classList.contains('is-invalid')){
                    swal("Atención", "Por favor verifique los campos en rojo.", "error");
                    return false;
                }
            }
            divLoading.style.display = "flex";

            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'Ventas/setAbono';
            let formData = new FormData(formAbono);
            request.open('POST',ajaxUrl, true);
            request.send(formData);

            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){ 
                        $('#modalAltaAbono').modal("hide");
                        formAbono.reset();
                        //swal("Clientes", objData.msg, "success");
                        verAbonos(idVenta);
                        $('#modalFormVentas').modal('handleUpdate');
                    }else{
                        swal("Error", objData.msg, "error");
                    }
                }
                divLoading.style.display = "none";
                return false;
            }

        }
    }
    // Fin de agregar abono...
    
        fntClientes2();
        fntProductosV();
        fntObtieneRegimen();
        fntObtieneCFDI();
        fntObtieneEstado();
        fntObtieneCiudad();
},false);

function fntClientes(){
    if(document.querySelector('#listCliente')){
        let ajaxUrl = base_url+'Clientes/getSelectClientes';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                //debugger;
                //console.log(request);
                document.querySelector('#listCliente').innerHTML = request.responseText;
                //document.querySelector('#listRolid').value=1; 
                $('#listCliente').selectpicker('render');      
            }
        }
    }
}

function fntClientes2(){
    //console.log("cliente: "+document.formVentas.querySelector('#listCliente').value);
    if(document.formVentas.querySelector('#listCliente')){
        let ajaxUrl = base_url+'Clientes/getSelectClientes';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.formVentas.querySelector('#listCliente').innerHTML = request.responseText;
                $('#listCliente').selectpicker('refresh');
                $('#listCliente').selectpicker('render');
                
                  
            }
        }
    }
}

function fntProductosV(){
    if(document.querySelector('#listProductos')){
        let ajaxUrl = base_url+'Productos/getSelectProductosV';
        let requestPV = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        requestPV.open("GET",ajaxUrl,true);
        requestPV.send();
        requestPV.onreadystatechange = function(){
            if(requestPV.readyState == 4 && requestPV.status == 200){
                document.querySelector('#listProductos').innerHTML = requestPV.responseText;
                $('#listProductos').selectpicker('render');  
            }
        }
    }
}


function fntViewInfo(venta){
    let idventa = venta; 
    //console.log(idpersona);
    let SMONEY = "MXN "; //Simbolo de moneda.
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Ventas/getVenta/'+idventa;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){ 
                
                let estadoVenta = objData.data.status;
                let htmlProds = '<table class="table table-bordered"><tbody><th class="col-md-3" style="text-align: center">Producto</th><th class="col-md-1" style="text-align: center">Imagen</th><th class="col-md-2" style="text-align: center">Cantidad</th><th class="col-md-2" style="text-align: center">Costo Venta</th><th class="col-md-2" style="text-align: center">Descuento</th><th class="col-md-2" style="text-align: center">Total</th>   ';
                let suma = parseFloat(0.0);
                let imp = parseFloat(0.0);
                let desc = parseFloat(0.0);
                let gtotal = parseFloat(0.0);
                let gdescuento = parseFloat(0.0);
                let idDetalle = 0;

                switch (estadoVenta) {
                    case "1":
                      estadoVenta = '<span class="badge badge-success">Activa</span>';
                      break;
                    case "2":
                        estadoVenta = '<span class="badge badge-info">Finalizada</span>';
                      break;
                    case "0":
                        estadoVenta = '<span class="badge badge-danger">Cancelada</span>';
                      break;
                  }

                document.querySelector("#celComprobante").innerHTML = objData.data.comprobante;
                document.querySelector("#celNombreCliente").innerHTML = objData.data.nombre_cliente;
                document.querySelector("#celDescuento").innerHTML = SMONEY+objData.data.sdescuento;
                //document.querySelector("#celImpuesto").innerHTML = objData.data.impuesto;
                document.querySelector("#celTotal").innerHTML = SMONEY+objData.data.total;
                //document.querySelector("#celPimpuesto").innerHTML = SMONEY+objData.data.pimpuesto;
                document.querySelector("#celGrantotal").innerHTML = SMONEY+objData.data.grantotal;
                document.querySelector("#celStatus").innerHTML = estadoVenta;
                document.querySelector("#celNotas").innerHTML = objData.data.notas;
                document.querySelector("#celFechaRegistro").innerHTML = objData.data.created_at;

                if(objData.data.productos.length > 0){
                    let objProds = objData.data.productos;
                    let preciov_tempo = 0;
                    let total_tempo = 0;
                    let pdesc=0.0;


                    for (let p = 0; p < objProds.length; p++) {
                        preciov_tempo = numeral(objProds[p].precio);
                        preciov_tempo = SMONEY+preciov_tempo.format('0,0.00');
                        
                        if(objProds[p].descuento>1){
                            pdesc=objProds[p].descuento/100;
                        }else{
                            pdesc=objProds[p].descuento;
                        }


                        descuentov_tempo = numeral((objProds[p].precio*objProds[p].cantidad)*pdesc);
                        descuentov_tempo = SMONEY+descuentov_tempo.format('0,0.00');

                        total_tempo = numeral((objProds[p].precio*objProds[p].cantidad)-((objProds[p].precio*objProds[p].cantidad)*pdesc));
                        total_tempo = SMONEY+total_tempo.format('0,0.00');

                        htmlProds +=`<tr class="odd" role="row">
                                            <td>${objProds[p].marca} ${objProds[p].nombre}</td>
                                            <td><img src="${base_url}Assets/images/uploads/${objProds[p].img}" width="72" height="52"></td>
                                            <td align="right">${objProds[p].cantidad}</td>
                                            <td align="right">${preciov_tempo}</td>
                                            <td align="right">${descuentov_tempo}</td>
                                            <td align="right">${total_tempo}</td>
                                        </tr>`;
                        desc       = parseFloat((objProds[p].precio*objProds[p].cantidad)*pdesc);
                        suma       = parseFloat(suma) + parseFloat((objProds[p].precio*objProds[p].cantidad)-desc);
                        gdescuento = parseFloat(gdescuento)+desc;
                    }
                }
                imp = parseFloat(suma) * parseFloat(objData.data.impuesto);
                gtotal = suma + imp;

            suma_tempo       = numeral(suma);
            imp_tempo        = numeral(imp);
            gtotal_tempo     = numeral(gtotal);
            gdescuento_tempo = numeral(gdescuento);

            suma_tempo       = SMONEY+suma_tempo.format('0,0.00');
            imp_tempo        = SMONEY+imp_tempo.format('0,0.00');
            gtotal_tempo     = SMONEY+gtotal_tempo.format('0,0.00');
            gdescuento_tempo = SMONEY+gdescuento_tempo.format('0,0.00');

                

                boton='<div class="col-12 text-right"><button type="button" class="btn btn-primary" onclick="fntImprimeRecibo('+idventa+');"><i class="fa fa-print"></i> Imprimir</button></div>';
                //htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td align="right">Subtotal</td><td align="right">'+suma_format+'</td></tr><tr><td></td><td></td><td></td><td align="right">Impuesto</td><td align="right">'+imp_format+'</td></tr><tr><td></td><td></td><td></td><td align="right">Total</td><td align="right">'+gtotal_format+'</td></tr></tbody></table>'+boton;
                htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td></td><td align="right">Total Venta</td><td align="right">'+gtotal_tempo+'</td></tr></tbody></table>'+boton;
                document.querySelector("#celProds").innerHTML = htmlProds;

                $('#modalViewVenta').modal('show');

            }else{
                swal("Error",objData.msg,'error');
            }
        }
    }
}

function fntDetalleVenta(){
    if(document.querySelector("#formVentas")){
        
        let formVentas = document.querySelector('#formVentas');
        let ven = document.querySelector('#idVenta').value;
        let impp = parseFloat(document.querySelector('#txtImpuesto').value);
        let prod = document.querySelector('#listProductos').value;
        let cant = document.querySelector('#intCantidad').value;
        let prec = document.querySelector('#intPrecio').value;
        let desc = document.querySelector('#intDesc').value;
        


        //console.log(ing);

        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'Ventas/setDetalleVenta';
        let formData = new FormData(formVentas);
        request.open('POST',ajaxUrl, true);
        request.send(formData);

        //console.log(request.status);
        
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                let objData = JSON.parse(request.responseText);
                //console.log(objData.status);
                obtener_tabla(ven, impp);

                if(!objData.status){
                    swal("Atención","No hay stock disponible.","error");
                }

            }
        };
                    
    }

}

function obtener_tabla(ven, impp){
    fntObtieneStock()
    let SMONEY = "MXN "; //Simbolo de moneda.
    let request2 = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl2 = base_url+'Ventas/getVenta/'+ven;
    request2.open("GET",ajaxUrl2,true);
    request2.send();

    request2.onreadystatechange = function(){

    if(request2.readyState == 4 && request2.status == 200){
        let objData2 = JSON.parse(request2.responseText);
        //console.log(objData);
        if(objData2.status){
            let arreglo = objData2.data.productos;
            //actualiza_impuesto(arreglo,ing);
        //console.log(arreglo);return;
            //tablita.api().ajax.reload();
            //$("#tablita").load(" #tablita");
            $("#tablita").remove();

            let htmlProds = '<table id="tablita" class="table table-bordered"><tbody><th class="col-md-1" style="text-align: center">Acción</th><th class="col-md-2" style="text-align: center">Producto</th><th class="col-md-1" style="text-align: center">Imagen</th><th class="col-md-1" style="text-align: center">Cantidad</th><th class="col-md-2" style="text-align: center">Costo</th><th class="col-md-1" style="text-align: center">% desc</th><th class="col-md-2" style="text-align: center">Descuento</th><th class="col-md-2" style="text-align: center">Total</th>   ';
            let suma       = parseFloat(0.0);
            let imp        = parseFloat(0.0);
            let desc       = parseFloat(0.0);
            let gtotal     = parseFloat(0.0);
            let gdescuento = parseFloat(0.0);
            let pdesc = 0.0;
            let pdesc2=0.0;
            let idDetalle  = 0;

            if(arreglo.length > 0){
                let objProds = arreglo;
                for (let p = 0; p < objProds.length; p++) {
                    let idDetalle = objProds[p].iddetalle_venta;

                    preciov_tempo = numeral(objProds[p].precio);
                    preciov_tempo = SMONEY+preciov_tempo.format('0,0.00');

                    if(objProds[p].descuento>1){
                        pdesc=objProds[p].descuento/100;
                        pdesc2=objProds[p].descuento;
                    }else{
                        pdesc=objProds[p].descuento;
                        pdesc2=objProds[p].descuento*100;
                    }


                    descuentov_tempo = numeral((objProds[p].precio*objProds[p].cantidad)*pdesc);
                    descuentov_tempo = SMONEY+descuentov_tempo.format('0,0.00');

                    total_tempo = numeral((objProds[p].precio*objProds[p].cantidad)-((objProds[p].precio*objProds[p].cantidad)*pdesc));
                    total_tempo = SMONEY+total_tempo.format('0,0.00');


                    htmlProds +=`<tr class="odd" role="row">
                        <td><div class="text-center"><button class="btn btn-danger btn-sm" type="button" onClick="fntBorrarDetalleVenta(${idDetalle});"><i class="far fa-trash-alt"></i></button></div></td>
                        <td>${objProds[p].marca} ${objProds[p].nombre}</td>
                        <td align="center"><img src="${base_url}Assets/images/uploads/${objProds[p].img}" width="72" height="52"></td>
                        <td id="canti${p}" align="right">${objProds[p].cantidad}</td>
                        <td id="precio${p}" align="right">${preciov_tempo}</td>
                        <td class="sm-1"><input  id="txtPorciento${p}" name="txtPorciento${p}" type="text" value=${pdesc2} onChange="cambia2('txtPorciento${p}', 'precio${p}', 'descu${p}', 'total${p}', 'canti${p}', ${idDetalle});"></td>
                        <td id="descu${p}" align="right">${descuentov_tempo}</td>
                        <td id="total${p}" align="right">${total_tempo}</td>
                        </tr>`;
                    desc       = parseFloat((objProds[p].precio*objProds[p].cantidad)*pdesc);
                    suma       = parseFloat(suma) + parseFloat((objProds[p].precio*objProds[p].cantidad)-desc);
                    gdescuento = parseFloat(gdescuento)+desc;
                }
            }
            imp = parseFloat(suma) * impp;
            gtotal = suma + imp;

            suma_tempo       = numeral(suma);
            imp_tempo        = numeral(imp);
            gtotal_tempo     = numeral(gtotal);
            gdescuento_tempo = numeral(gdescuento);

            suma_tempo       = SMONEY+suma_tempo.format('0,0.00');
            imp_tempo        = SMONEY+imp_tempo.format('0,0.00');
            gtotal_tempo     = SMONEY+gtotal_tempo.format('0,0.00');
            gdescuento_tempo = SMONEY+gdescuento_tempo.format('0,0.00');

            //htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td></td><td></td><td align="right">Subtotal</td><td align="right">'+suma_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Impuesto</td><td align="right">'+imp_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Descuento</td><td align="right">'+gdescuento_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Total</td><td align="right">'+gtotal_tempo+'</td></tr></tbody></table>';
            htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td></td><td></td><td></td><td align="right">Total</td><td id="gtotal" align="right">'+gtotal_tempo+'</td></tr></tbody></table>';
            document.querySelector("#tableProductos").innerHTML = htmlProds;
            //onClick="fntDelInfo('.$arrData[$i]['idingreso'].')" title="Cancelar orden de compra"
            actualiza_impuesto(arreglo,impp);    
            
            if(rowTable == ""){
                // Si es una venta  nuevo, refresca la tabla y manda a la primera pagina
                tableVentas.api().ajax.reload();    
            }else{
                let SMONEY = "MXN "; //Simbolo de moneda.
                // Si es edicion, se mantiene en la pagina actual y solo modifica el renglon.
                rowTable.cells[0].textContent = objData.data.nombre_cliente;
                rowTable.cells[1].textContent = document.querySelector('#txtComprobante').value ;
                rowTable.cells[2].textContent = sumasubt;
                //rowTable.cells[3].textContent = SMONEY+pimp;
                rowTable.cells[3].textContent = gdescuento_tempo;
                rowTable.cells[4].textContent = gtotal_tempo;
                rowTable.cells[5].innerHTML = estadoVenta;
                rowTable = "";
                
            }
        }
    }
    }  
}

function fntEditInfo(element, venta){     
    // Esta variable rowTable, tome todo el valor de la fila de la tabla.  
    rowTable = element.parentNode.parentNode.parentNode;
    //rowTable.cells[1].textContent
    //console.log(rowTable);  
    document.querySelector('#titleModal').innerHTML = "Actualizar Orden de Salida";
    document.querySelector('.modal-header').classList.replace("headerRegister","headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary","btn-info");
    document.querySelector('#btnText').innerHTML= "Actualizar";
    
    let SMONEY = "MXN "; //Simbolo de moneda.
    let idventa = venta;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Ventas/getVenta/'+idventa;
    request.open("GET",ajaxUrl,true);
    request.send();


    request.onreadystatechange = function(){

        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            //console.log(objData);
            if(objData.status){
                //fntClientes2();
                
                document.querySelector('#FrmProductos').classList.remove("notBlock");
                document.querySelector('#tableProductos').classList.remove("notBlock");
                
                document.querySelector('#idVenta').value = objData.data.idventa;
                document.querySelector('#listCliente').value = objData.data.idcliente;
                document.querySelector('#txtComprobante').value = objData.data.comprobante;
                document.querySelector('#txtImpuesto').value = objData.data.impuesto;
                document.querySelector('#txtSubtotal').value = objData.data.total;
                document.querySelector('#txtPimpuesto').value = objData.data.pimpuesto;
                //document.querySelector('#txtDescuento').value = objData.data.descuento;
                document.querySelector('#txtTotal').value = objData.data.grantotal;
                document.querySelector('#txtNotas').value = objData.data.notas;
                tinymce.activeEditor.setContent(objData.data.notas); 
                document.querySelector("#listStatus").value = objData.data.status;
                $('#listCliente').selectpicker('render');
                $('#listStatus').selectpicker('render');

                let arreglo = objData.data.productos;
                let htmlProds = '<table id="tablita" class="table table-bordered"><tbody><th class="col-md-1" style="text-align: center">Acción</th><th class="col-md-2" style="text-align: center">Producto</th><th class="col-md-1" style="text-align: center">Imagen</th><th class="col-md-1" style="text-align: center">Cantidad</th><th class="col-md-2" style="text-align: center">Costo</th><th class="col-md-1" style="text-align: center">% desc</th><th class="col-md-2" style="text-align: center">Descuento</th><th class="col-md-2" style="text-align: center">Total</th>   ';
                let suma = parseFloat(0.0);
                let imp = parseFloat(0.0);
                let gtotal = parseFloat(0.0);
                let desc       = parseFloat(0.0);
                let gdescuento = parseFloat(0.0);
                let pdesc= 0.0;
                let pdesc2= 0.0;
                let sumasub=0.0;
                let sumasubt=0.0;
                let total_tempo=0.0;
                let estadoVenta = "";

                let idDetalle = 0;
                if(objData.data.productos.length > 0){
                    let objProds = objData.data.productos;
                    for (let p = 0; p < objProds.length; p++) {

                        preciov_tempo = numeral(objProds[p].precio);
                        preciov_tempo = SMONEY+preciov_tempo.format('0,0.00');

                        if(objProds[p].descuento>1){
                            pdesc=objProds[p].descuento/100;
                            pdesc2=objProds[p].descuento;
                        }else{
                            pdesc=objProds[p].descuento;
                            pdesc2=objProds[p].descuento*100;
                        }


                        descuentov_tempo = numeral((objProds[p].precio*objProds[p].cantidad)*pdesc);
                        descuentov_tempo = SMONEY+descuentov_tempo.format('0,0.00');

                        total_tempo = numeral((objProds[p].precio*objProds[p].cantidad)-((objProds[p].precio*objProds[p].cantidad)*pdesc));
                        total_tempo = SMONEY+total_tempo.format('0,0.00');


                        let idDetalle = objProds[p].iddetalle_venta;
                        
                        htmlProds +=`<tr class="odd" role="row">
                                            <td><div class="text-center"><button class="btn btn-danger btn-sm" type="button" onClick="fntBorrarDetalleVenta(${idDetalle});"><i class="far fa-trash-alt"></i></button></div></td>
                                            <td>${objProds[p].marca} ${objProds[p].nombre}</td>
                                            <td><img src="${base_url}Assets/images/uploads/${objProds[p].img}" width="72" height="52"></td>
                                            <td id="canti${p}" align="right">${objProds[p].cantidad}</td>
                                            <td id="precio${p}" align="right">${preciov_tempo}</td>
                                            <td class="sm-1"><input  id="txtPorciento${p}" name="txtPorciento${p}" type="text" value=${pdesc2} onChange="cambia2('txtPorciento${p}', 'precio${p}', 'descu${p}', 'total${p}', 'canti${p}', ${idDetalle});"></td>
                                            <td id="descu${p}" align="right">${descuentov_tempo}</td>
                                            <td id="total${p}" align="right">${total_tempo}</td>
                                        </tr>`;
                        
                        desc       = parseFloat((objProds[p].precio*objProds[p].cantidad)*pdesc);
                        suma       = parseFloat(suma) + parseFloat((objProds[p].precio*objProds[p].cantidad)-desc);
                        sumasub    = parseFloat(sumasub) + parseFloat((objProds[p].precio*objProds[p].cantidad));
                        gdescuento = parseFloat(gdescuento)+desc;
                    }
                }
                impp = document.querySelector('#txtImpuesto').value;
                imp = parseFloat(suma) * impp;
                gtotal = suma + imp;

                suma_tempo       = numeral(suma);
                imp_tempo        = numeral(imp);
                gtotal_tempo     = numeral(gtotal);
                gdescuento_tempo = numeral(gdescuento);
                sumasubt         = numeral(sumasub);

                suma_tempo       = SMONEY+suma_tempo.format('0,0.00');
                imp_tempo        = SMONEY+imp_tempo.format('0,0.00');
                gtotal_tempo     = SMONEY+gtotal_tempo.format('0,0.00');
                gdescuento_tempo = SMONEY+gdescuento_tempo.format('0,0.00');
                sumasubt         = SMONEY+sumasubt.format('0,0.00');

                switch (document.querySelector("#listStatus").value) {
                    case "1":
                        estadoVenta = '<span class="badge badge-success">Activa</span>';
                      break;
                    case "2":
                        estadoVenta = '<span class="badge badge-info">Finalizada</span>';
                      break;
                    case "0":
                        estadoVenta = '<span class="badge badge-danger">Cancelada</span>';
                      break;
                  }

                //htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td></td><td></td><td align="right">Subtotal</td><td align="right">'+suma_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Impuesto</td><td align="right">'+imp_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Descuento</td><td align="right">'+gdescuento_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Total</td><td align="right">'+gtotal_tempo+'</td></tr></tbody></table>';
                htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td></td><td></td><td></td><td align="right">Total</td><td id="gtotal" align="right">'+gtotal_tempo+'</td></tr></tbody></table>';
                document.querySelector("#tableProductos").innerHTML = htmlProds;
                //onClick="fntDelInfo('.$arrData[$i]['idingreso'].')" title="Cancelar orden de compra"
                actualiza_impuesto(arreglo,objData.data.impuesto);

                if(rowTable == ""){
                    // Si es una venta  nuevo, refresca la tabla y manda a la primera pagina
                    tableVentas.api().ajax.reload();    
                }else{
                    let SMONEY = "MXN "; //Simbolo de moneda.
                    // Si es edicion, se mantiene en la pagina actual y solo modifica el renglon.
                    rowTable.cells[0].textContent = objData.data.nombre_cliente;
                    rowTable.cells[1].textContent = document.querySelector('#txtComprobante').value ;
                    rowTable.cells[2].textContent = sumasubt;
                    //rowTable.cells[3].textContent = SMONEY+pimp;
                    rowTable.cells[3].textContent = gdescuento_tempo;
                    rowTable.cells[4].textContent = gtotal_tempo;
                    rowTable.cells[5].innerHTML = estadoVenta;
                    rowTable = "";
                    
                }
                
                
            }
            // Carga los abonos de la venta...
            verAbonos(idventa);
        }
        $('#modalFormVentas').modal('show');
        
        
    }
    
}

function fntBorrarDetalleVenta(idDetalleVenta){
    let idDetalle = idDetalleVenta;
    let ven = document.querySelector('#idVenta').value;
    let impp = parseFloat(document.querySelector('#txtImpuesto').value);
    //alert("Ingreso: "+ing+" Impuesto: "+impp);
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest():new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Ventas/delDetalleVenta/'+idDetalle;
    request.open("POST",ajaxUrl,true);
    //request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            /*if(objData.status){
                swal("Eliminar",objData.msg,"success");
                tableIngresos.api().ajax.reload();
            }else{
                swal("Atención",objData.msg,"error");
            }*/
            obtener_tabla(ven, impp);
            fntObtieneStock();
        }
    }
}

function fntActualizaDetalleVenta(idDetalleVenta,pctg){
    let idDetalle = idDetalleVenta;
    let porcenta  = pctg;
    let datos = {
        iddet: idDetalle, // Dato #1 a enviar
        porc: porcenta // Dato #2 a enviar
    };

    let ven = document.querySelector('#idVenta').value;
    let impp = parseFloat(document.querySelector('#txtImpuesto').value);

    let ajaxUrl = base_url+'Ventas/actualizaporcen/';
    let request=enviarDatos(datos, ajaxUrl);
    
    $('#listProductos').focus();
}

function enviarDatos(datos, url){
    $.ajax({
            data: datos,
            url: url,
            type: 'post',
            success:  function (response) {
                //console.log(response); // Imprimir respuesta del archivo
                return response;
            },
            error: function (error) {
                //console.log(error); // Imprimir respuesta de error
                return error;
            }
    });
    $('#listProductos').focus();
}

function fntDelInfo(idventa){
    let idVenta = idventa;
    
    swal({
        title: "Eliminar Orden de Salida",
        text: "¿Realmente quieres eliminar la orden de salida?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm){
        if (isConfirm){
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest():new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'Ventas/delVenta/';
            let strData = "idVenta="+idVenta;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Eliminar",objData.msg,"success");
                        tableVentas.api().ajax.reload();
                    }else{
                        swal("Atención",objData.msg,"error");
                    }
                }
            }
        }
    });
}

function actualiza_impuesto(arreglo, impuesto){
    //console.log(arreglo);
    //console.log(impuesto);
    let suma_subtotal = 0.0;
    let p_impuesto = 0.0;
    let g_total = 0.0;
    let descuento = 0.0
    let subtotal = 0.0;
    let pdesc = 0.0;

    for (let p = 0; p < arreglo.length; p++) {
        if(parseFloat(arreglo[p].descuento)>1){
            pdesc = parseFloat(arreglo[p].descuento)/100;
        }else{
            pdesc = parseFloat(arreglo[p].descuento);
        }
        descuento = descuento + parseFloat((parseFloat(arreglo[p].cantidad)*parseFloat(arreglo[p].precio))*pdesc);
        suma_subtotal = suma_subtotal+((parseFloat(arreglo[p].cantidad)*parseFloat(arreglo[p].precio))  - (parseFloat((parseFloat(arreglo[p].cantidad)*parseFloat(arreglo[p].precio))*pdesc)) );
        subtotal = subtotal+((parseFloat(arreglo[p].cantidad)*parseFloat(arreglo[p].precio)));
        }
    p_impuesto = parseFloat(impuesto);
    p_impuesto = suma_subtotal * p_impuesto;
    g_total = suma_subtotal + p_impuesto;
    
    document.querySelector('#txtSubtotal').value = subtotal.toFixed(2);
    document.querySelector('#txtPimpuesto').value = p_impuesto.toFixed(2);
    document.querySelector('#txtTotal').value = g_total.toFixed(2);
    document.querySelector('#txtDescuento').value = descuento.toFixed(2);

    if(document.querySelector('#txtTotal').value>0){
        document.querySelector('#btnFinaliza').classList.remove("notBlock");
    }else{
        document.querySelector('#btnFinaliza').classList.add("notBlock");
    }
}

function openModal(){
    rowTable = "";
    document.querySelector('#idVenta').value="";
    //verAbonos('');
    document.querySelector('.modal-header').classList.replace("headerUpdate", "HeaderRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Orden de salida";
    document.querySelector('#formVentas').reset();
    document.querySelector('#FrmProductos').classList.add("notBlock");
    document.querySelector('#tableProductos').classList.add("notBlock");
    $('#modalFormVentas').modal('show');
    if(document.querySelector('#idVenta').value == 0){
        fntComprobanteMax();
    }
}

function fntAlertaInventario(){
    let valor = document.querySelector("#listStatus").value ;
    if(valor > 1){
        swal("Atención", "Al finalizar la orden de salida, se actualizara el inventario.", "info");
    }
}

function finaliza(){
    swal({
        title: "Finalizar Orden",
        text: "¿Realmente quieres finalizar la orden de salida?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, finalizar",
        cancelButtonText: "No, regresar y actualizar",
        closeOnConfirm: true,
        closeOnCancel: true
    }, function(isConfirm){
        if (isConfirm){
            document.querySelector("#listStatus").value = 2;

            let requestx = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrlx = base_url+'Ventas/setVenta';
            let formDatax = new FormData(formVentas);
            requestx.open('POST',ajaxUrlx, true);
            requestx.send(formDatax);
            requestx.onreadystatechange = function(){
                if(requestx.readyState == 4 && requestx.status == 200){
                    $('#modalFormVentas').modal('hide');
                    let ven = document.querySelector("#idVenta").value;
                    let ajaxUrl = base_url+'Ventas/ticket/'+ven;
                    //let ajaxUrl = base_url+'Ventas';
                    window.open(ajaxUrl,true);
                    
                    
                }
            }
            

            //alert(document.querySelector("#listStatus").value+" "+document.querySelector('#listCliente').value);
        }
    });
    //return;
}

function fntObtieneStock(){
    let prod = document.querySelector("#listProductos").value;
    if(parseInt(prod)>=0){
    let requestOS = (window.XMLHttpRequest) ? new XMLHttpRequest():new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Productos/getProductoV/'+prod;
    requestOS.open("POST",ajaxUrl,true);
    requestOS.send();
    requestOS.onreadystatechange = function(){
        if(requestOS.readyState == 4 && requestOS.status == 200){
            let objDataOS = JSON.parse(requestOS.responseText);
            document.querySelector('#cantistock').innerHTML = ' ('+objDataOS.data.stock+')';
            //document.querySelector('#intCantidad').value = objDataOS.data.stock;
            document.querySelector('#intCantidad').value = 1;
            document.querySelector('#intPrecio').value = objDataOS.data.precio;
        }
    }
    }
    $('#listProductos').focus();
}

function fntImprimeRecibo(idventa){
    let ven = idventa;
    let ajaxUrl = base_url+'Ventas/ticket/'+ven;
    //requestOS.open("POST",ajaxUrl,true);
    //requestOS.send();
    window.open(ajaxUrl,'_blank');
    /*
    requestOS.onreadystatechange = function(){
        if(requestOS.readyState == 4 && requestOS.status == 200){
            let objDataOS = JSON.parse(requestOS.responseText);
                //console.log(objDataOS);
                //window.location="ticket.php?ticket="+ven;
                
                
        }
    }
    */
}

function fntComprobanteMax(){
    let ajaxUrl = base_url+'Ventas/getMaxComprobante';
    let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                let objP = JSON.parse(request.responseText);
                if(objP.status)
                { 
                    document.querySelector("#txtComprobante").value = objP.comprobante;
                    //console.log(objP.codigo);
                }

            }
        }
}

function muestraRFC(){
    if (document.querySelector('#fiscales').classList.contains("notBlock")){
        document.querySelector('#fiscales').classList.remove("notBlock");
    }else{
        document.querySelector('#fiscales').classList.add("notBlock");
    }
    //$('#fiscales').fadeToggle();
}

function fntEmailAle(){
    let ajaxUrl = base_url+'Clientes/getMailAle';
    let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                let objP = JSON.parse(request.responseText);
                if(objP.status)
                { 
                    document.querySelector("#txtEmail").value = objP.msg;
                    document.querySelector("#txtNombre").focus();
                    //$('#txtNombre').focus();
                }

            }
        }
}
/*
function fntEmailAle(){
    let ajaxUrl = base_url+'Clientes/getMailAle';
    let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                let objP = JSON.parse(request.responseText);
                if(objP.status)
                { 
                    document.querySelector("#txtEmail").value = objP.msg;
                    document.querySelector("#txtNombre").focus();
                    //$('#txtNombre').focus();
                }

            }
        }
}
*/

function nuevoCliente(){
    document.querySelector('.modal-header').classList.replace("headerRegister","header-primary");
    $('#modalAltaCliente').modal('show');
    fntEmailAle();
}

function nuevoAbono(){
    document.querySelector('.modal-header').classList.replace("headerRegister","header-primary");
    $('#modalAltaAbono').modal('show');
}

function cambia2(a, b, c, d, e, iddet){
    //a Porcentaje a cambiar
    //b precio
    //c descuento 
    //d total
    //e cantidad
    //iddet detalle
    
     //console.log(iddet);

    let text="";
    let textt="";
    let SMONEY="MXN ";
    
    //Obtiene el total anterior, asi como el impuesto y subtotal anterior.
    let gtanterior = eval(gtotal).innerHTML;
    text=gtanterior;
    textt=text.replace("MXN", "");
    text=textt.replace(",", "");
    gtanterior=text;


    let tanterior = eval(d).innerHTML;
    text=tanterior;
    textt=text.replace("MXN", "");
    text=textt.replace(",", "");
    tanterior=text;

    // Realiza los demas calculos...

    text=eval(b).innerHTML;
    textt=text.replace("MXN", "");
    text=textt.replace(",", "");
    eval(b).innerHTML=text;

    text=eval(c).innerHTML;
    textt=text.replace("MXN", "");
    text=textt.replace(",", "");
    eval(c).innerHTML=text;

    text=eval(e).innerHTML;
    textt=text.replace("MXN", "");
    text=textt.replace(",", "");
    eval(e).innerHTML=text;

    let dess=0.0;
    let ptg=0.0;

    dess=parseFloat(eval(b).innerHTML*eval(e).innerHTML);
    ptg=parseFloat(eval(a).value);
  
    if (ptg>=1){
      ptg=ptg/100;
    }
    
    dess=dess*ptg;
  
    eval(c).innerHTML=SMONEY+numeral(dess).format('0,0.00');

    text=eval(c).innerHTML;
    eval(c).innerHTML=text.replace("MXN", "");
  
    dess=(parseFloat(eval(b).innerHTML*eval(e).innerHTML))- parseFloat(eval(c).innerHTML);
  
    eval(c).innerHTML=SMONEY+numeral(eval(c).innerHTML).format('0,0.00');
    eval(d).innerHTML=SMONEY+numeral(dess).format('0,0.00');
    eval(b).innerHTML=SMONEY+numeral(eval(b).innerHTML).format('0,0.00');
    //SMONEY+suma_tempo.format('0,0.00');

    //Calcula el nuevo total...
    //let gtanterior = eval(gtotal).innerHTML;
    //let tanterior = eval(d).innerHTML;

    //Calculo del gran total...
    
    gtanterior=(gtanterior-tanterior)+dess;
    eval(gtotal).innerHTML = SMONEY+numeral(gtanterior).format('0,0.00');

    text=document.querySelector('#txtDescuento').value;
    textt=text.replace("MXN", "");
    text=textt.replace(",", "");
    let descante=text;

    text=document.querySelector('#txtSubtotal').value;
    textt=text.replace("MXN", "");
    text=textt.replace(",", "");
    let subtota=text;

    descante=subtota-gtanterior;

    //let subtante=gtanterior-tanterior;
    
    
    //alert("gt anterior= "+gtanterior+" tanterior= "+tanterior);
    
    //document.querySelector('#txtSubtotal').value = subtotal.toFixed(2);
    //document.querySelector('#txtPimpuesto').value = p_impuesto.toFixed(2);
    document.querySelector('#txtTotal').value = SMONEY+numeral(gtanterior).format('0,0.00');
    document.querySelector('#txtSubtotal').value = SMONEY+numeral(subtota).format('0,0.00');
    document.querySelector('#txtDescuento').value = SMONEY+numeral(descante).format('0,0.00');
    //document.querySelector('#txtDescuento').value = descuento.toFixed(2);

    fntActualizaDetalleVenta(parseInt(iddet),parseFloat(ptg));
  }

  

// Para filtros de estado, ciudad, CFDI...

function fntObtieneRegimen(){
    if(document.querySelector('#listRegimen')){
        let ajaxUrl = base_url+'Clientes/getSelectRegimen';
        let requestRe = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        requestRe.open("GET",ajaxUrl,true);
        requestRe.send();
        requestRe.onreadystatechange = function(){
            if(requestRe.readyState == 4 && requestRe.status == 200){
                document.querySelector('#listRegimen').innerHTML = requestRe.responseText;
                $('#listRegimen').selectpicker('render');  
            }
        }
    }
}

function fntObtieneCFDI(){
    //alert("si");
    if(document.querySelector('#listCFDI')){
        let ajaxUrl = base_url+'Clientes/getSelectCFDI/'+document.querySelector('#listRegimen').value;
        let requestRe = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        requestRe.open("GET",ajaxUrl,true);
        requestRe.send();
        requestRe.onreadystatechange = function(){
            if(requestRe.readyState == 4 && requestRe.status == 200){
                document.querySelector('#listCFDI').innerHTML = '';
            $('#listCFDI').selectpicker('destroy');

                document.querySelector('#listCFDI').innerHTML = requestRe.responseText;
                $('#listCFDI').selectpicker('get');
                //$('#listCFDI').selectpicker('render');  
            }
        }
    }
}



function fntCambioCFDI(){   
    let ajaxUrl = base_url+'Clientes/getSelectCFDI/'+document.querySelector('#listRegimen').value;
    let requestRe = (window.XMLHttpRequest) ? 
                new XMLHttpRequest() : 
                new ActiveXObject('Microsoft.XMLHTTP');
    requestRe.open("GET",ajaxUrl,true);
    requestRe.send();
    requestRe.onreadystatechange = function(){
        if(requestRe.readyState == 4 && requestRe.status == 200){
            
            document.querySelector('#listCFDI').innerHTML = '';
            $('#listCFDI').selectpicker('destroy');

            document.querySelector('#listCFDI').innerHTML = requestRe.responseText;
            $('#listCFDI').selectpicker('get');
            $('#listCFDI').selectpicker('render');  
            
        }
    }
}

function fntCambioCiudad(){   
    let ajaxUrl = base_url+'Clientes/getSelectCiudad/'+document.querySelector('#listEstado').value;
    let requestRe = (window.XMLHttpRequest) ? 
                new XMLHttpRequest() : 
                new ActiveXObject('Microsoft.XMLHTTP');
    requestRe.open("GET",ajaxUrl,true);
    requestRe.send();
    requestRe.onreadystatechange = function(){
        if(requestRe.readyState == 4 && requestRe.status == 200){
            
            document.querySelector('#listCiudad').innerHTML = '';
            $('#listCiudad').selectpicker('destroy');

            document.querySelector('#listCiudad').innerHTML = requestRe.responseText;
            $('#listCiudad').selectpicker('get');
            $('#listCiudad').selectpicker('render');  
            
        }
    }

}


function fntObtieneEstado(){
    if(document.querySelector('#listEstado')){
        let ajaxUrl = base_url+'Clientes/getSelectEstado';
        let requestRe = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        requestRe.open("GET",ajaxUrl,true);
        requestRe.send();
        requestRe.onreadystatechange = function(){
            if(requestRe.readyState == 4 && requestRe.status == 200){
                document.querySelector('#listEstado').innerHTML = requestRe.responseText;
                $('#listEstado').selectpicker('render');  
            }
        }
    }
}

function fntObtieneCiudad(){
    if(document.querySelector('#listCiudad')){
        let ajaxUrl = base_url+'Clientes/getSelectCiudad/'+document.querySelector('#listEstado').value;
        let requestRe = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        requestRe.open("GET",ajaxUrl,true);
        requestRe.send();
        requestRe.onreadystatechange = function(){
            if(requestRe.readyState == 4 && requestRe.status == 200){
                document.querySelector('#listCiudad').innerHTML = '';
                $('#listCiudad').selectpicker('destroy');

                document.querySelector('#listCiudad').innerHTML = requestRe.responseText;
                $('#listCiudad').selectpicker('get');
                $('#listCiudad').selectpicker('render');  
            }
        }
    }
}

function verAbonos(idventa){
    //let ajaxUrl = base_url+'Ventas/getAbonos/'+document.querySelector('#idVenta').value;
    let tabonos = 0;
    let ajaxUrl = base_url+'Ventas/getAbonos/'+idventa;
        let requestA = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        requestA.open("GET",ajaxUrl,true);
        requestA.send();
        requestA.onreadystatechange = function(){
            if(requestA.readyState == 4 && requestA.status == 200){
                
                let objDataA = JSON.parse(requestA.responseText);
                let arreglo = objDataA;
                //console.log(objDataA);
               //console.log(arreglo);
               //$("#tabla_abonos").remove();
               document.querySelector("#tabla_abonos").innerHTML = '';

               let htmlAbonos = '<table id="tabla_abonos" class="table table-bordered"><tbody><th class="col-md-1" style="text-align: center">Fecha</th><th class="col-md-2" style="text-align: center">Abono</th><th class="col-md-1" style="text-align: center">Acción</th>';
                if(arreglo.length > 0){
                    let objAbonos = arreglo;
                    //console.log(objAbonos);
                    for (let p = 0; p < objAbonos.length; p++) {
                        let idVenta = objAbonos[p].idventa;
                        tabonos = objAbonos[p].suma;
                        //console.log(objAbonos[p].abono);
                        htmlAbonos +=`<tr class="odd" role="row">
                            <td id="fechaa${p}" align="center">${objAbonos[p].fechaabono}</td>
                            <td id="abonoa${p}" align="right">${objAbonos[p].abono}</td>
                            <td><div class="text-center"><button class="btn btn-danger btn-sm" type="button" onClick="fntBorrarAbono(${objAbonos[p].idabono});"><i class="far fa-trash-alt"></i></button></div></td>
                            </tr>`;
                    }
                    //console.log(htmlAbonos);exit;
                }
                htmlAbonos=htmlAbonos+'<tr><td class="col-md-1" style="text-align: center">Total Abonos:</td><td style="text-align: right">'+tabonos+'</td><td></td></tr></tbody></table>';
                document.querySelector("#tabla_abonos").innerHTML = htmlAbonos;

                
            }
        }
}

function fntBorrarAbono(idAbono){
    let abono = idAbono;
    //let ven = document.querySelector('#idVenta').value;
    
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest():new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Ventas/delAbono/'+abono;
    request.open("POST",ajaxUrl,true);
    //request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            verAbonos(document.querySelector("#idVenta").value);
            /*if(objData.status){
                swal("Eliminar",objData.msg,"success");
                tableIngresos.api().ajax.reload();
            }else{
                swal("Atención",objData.msg,"error");
            }*/
            //obtener_tabla(ven, impp);
            //fntObtieneStock();
        }
    }

}

function modiPicker(){
    //var fecha = document.formAbono.querySelector('#txtFecha');
    let fecha = document.getElementById('txtFecha');
    //fecha.style.cssText = '.ui-datepicker-calendar{display: flex;}';
    fecha.classList.remove('ui-datepicker-calendar');
}

$('.date-picker').datepicker( {
    closeText: 'Cerrar',
	prevText: '<Ant',
	nextText: 'Sig>',
	currentText: 'Hoy',
	monthNames: ['1 -', '2 -', '3 -', '4 -', '5 -', '6 -', '7 -', '8 -', '9 -', '10 -', '11 -', '12 -'],
	monthNamesShort: ['Enero','Febrero','Marzo','Abril', 'Mayo','Junio','Julio','Agosto','Septiembre', 'Octubre','Noviembre','Diciembre'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié;', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    eekHeader: 'Sm',
    firstDay: 1,
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'dd/mm/yy',
    isRTL: false,
    showDays: true,
    showWeekDays: true,
    todayHighlight: true,
    onClose: function(dateText, inst) {
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay));
    }
});


