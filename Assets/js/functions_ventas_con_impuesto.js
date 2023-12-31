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
                                    let htmlProds = '<table id="tablita" class="table table-bordered"><tbody><th class="col-md-1" style="text-align: center">Acción</th><th class="col-md-2" style="text-align: center">Producto</th><th class="col-md-1" style="text-align: center">Imagen</th><th class="col-md-1" style="text-align: center">Cantidad</th><th class="col-md-2" style="text-align: center">Costo</th><th class="col-md-2" style="text-align: center">Descuento</th><th class="col-md-2" style="text-align: center">Total</th>   ';
                                    let suma = parseFloat(0.0);
                                    let imp = parseFloat(0.0);
                                    let gtotal = parseFloat(0.0);
                                    let desc       = parseFloat(0.0);
                                    let gdescuento = parseFloat(0.0);
                    
                                    let idDetalle = 0;
                                    if(objData.data.productos.length > 0){
                                        let objProds = objData.data.productos;
                                        for (let p = 0; p < objProds.length; p++) {
                    
                                            preciov_tempo = numeral(objProds[p].precio);
                                            preciov_tempo = SMONEY+preciov_tempo.format('0,0.00');
                    
                                            descuentov_tempo = numeral((objProds[p].precio*objProds[p].cantidad)*objProds[p].descuento);
                                            descuentov_tempo = SMONEY+descuentov_tempo.format('0,0.00');
                    
                                            total_tempo = numeral((objProds[p].precio*objProds[p].cantidad)-((objProds[p].precio*objProds[p].cantidad)*objProds[p].descuento));
                                            total_tempo = SMONEY+total_tempo.format('0,0.00');
                    
                    
                                            let idDetalle = objProds[p].iddetalle_venta;
                                            htmlProds +=`<tr class="odd" role="row">
                                                                <td><div class="text-center"><button class="btn btn-danger btn-sm" type="button" onClick="fntBorrarDetalleVenta(${idDetalle});"><i class="far fa-trash-alt"></i></button></div></td>
                                                                <td>${objProds[p].marca} ${objProds[p].nombre}</td>
                                                                <td><img src="${base_url}Assets/images/uploads/${objProds[p].img}" width="72" height="52"></td>
                                                                <td align="right">${objProds[p].cantidad}</td>
                                                                <td align="right">${preciov_tempo}</td>
                                                                <td align="right">${descuentov_tempo}</td>
                                                                <td align="right">${total_tempo}</td>
                                                            </tr>`;
                                            
                                            desc       = parseFloat((objProds[p].precio*objProds[p].cantidad)*objProds[p].descuento);
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
                    
                    
                    
                                    htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td></td><td></td><td align="right">Subtotal</td><td align="right">'+suma_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Impuesto</td><td align="right">'+imp_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Descuento</td><td align="right">'+gdescuento_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Total</td><td align="right">'+gtotal_tempo+'</td></tr></tbody></table>';
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
    
    fntClientes();
    fntProductosV();

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
                let htmlProds = '<table class="table table-bordered"><tbody><th class="col-md-4" style="text-align: center">Producto</th><th class="col-md-1" style="text-align: center">Imagen</th><th class="col-md-2" style="text-align: center">Cantidad</th><th class="col-md-2" style="text-align: center">Costo Venta</th><th class="col-md-2" style="text-align: center">Total</th>   ';
                let suma = parseFloat(0.0);
                let imp = parseFloat(0.0);
                let gtotal = parseFloat(0.0);
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
                document.querySelector("#celImpuesto").innerHTML = objData.data.impuesto;
                document.querySelector("#celTotal").innerHTML = SMONEY+objData.data.total;
                document.querySelector("#celPimpuesto").innerHTML = SMONEY+objData.data.pimpuesto;
                document.querySelector("#celGrantotal").innerHTML = SMONEY+objData.data.grantotal;
                document.querySelector("#celStatus").innerHTML = estadoVenta;
                document.querySelector("#celNotas").innerHTML = objData.data.notas;
                document.querySelector("#celFechaRegistro").innerHTML = objData.data.created_at;

                if(objData.data.productos.length > 0){
                    let objProds = objData.data.productos;
                    let preciov_tempo = 0;
                    let total_tempo = 0;


                    for (let p = 0; p < objProds.length; p++) {
                        preciov_tempo = numeral(objProds[p].precio);
                        preciov_tempo = SMONEY+preciov_tempo.format('0,0.00');

                        total_tempo = numeral(objProds[p].preciov*objProds[p].cantidad);
                        total_tempo = SMONEY+total_tempo.format('0,0.00');

                        htmlProds +=`<tr class="odd" role="row">
                                            <td>${objProds[p].marca} ${objProds[p].nombre}</td>
                                            <td><img src="${base_url}Assets/images/uploads/${objProds[p].img}" width="72" height="52"></td>
                                            <td align="right">${objProds[p].cantidad}</td>
                                            <td align="right">${preciov_tempo}</td>
                                            <td align="right">${total_tempo}</td>
                                        </tr>`;
                        suma = parseFloat(suma) + parseFloat(objProds[p].preciov*objProds[p].cantidad);
                    }
                }
                imp = parseFloat(suma) * parseFloat(objData.data.impuesto);
                gtotal = suma + imp;

                // Formatea a moneda cantidades totales
                let suma_format = numeral (suma);
                suma_format = SMONEY+suma_format.format('0,0.00');

                let imp_format = numeral (imp);
                imp_format = SMONEY+imp_format.format('0,0.00');

                let gtotal_format = numeral (gtotal);
                gtotal_format = SMONEY+gtotal_format.format('0,0.00');

                

                boton='<div class="col-12 text-right"><button type="button" class="btn btn-primary" onclick="fntImprimeRecibo('+idventa+');"><i class="fa fa-print"></i> Imprimir</button></div>';
                htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td align="right">Subtotal</td><td align="right">'+suma_format+'</td></tr><tr><td></td><td></td><td></td><td align="right">Impuesto</td><td align="right">'+imp_format+'</td></tr><tr><td></td><td></td><td></td><td align="right">Total</td><td align="right">'+gtotal_format+'</td></tr></tbody></table>'+boton;
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

            let htmlProds = '<table id="tablita" class="table table-bordered"><tbody><th class="col-md-1" style="text-align: center">Acción</th><th class="col-md-2" style="text-align: center">Producto</th><th class="col-md-1" style="text-align: center">Imagen</th><th class="col-md-1" style="text-align: center">Cantidad</th><th class="col-md-2" style="text-align: center">Costo</th><th class="col-md-2" style="text-align: center">Descuento</th><th class="col-md-2" style="text-align: center">Total</th>   ';
            let suma       = parseFloat(0.0);
            let imp        = parseFloat(0.0);
            let desc       = parseFloat(0.0);
            let gtotal     = parseFloat(0.0);
            let gdescuento = parseFloat(0.0);
            let idDetalle  = 0;

            if(arreglo.length > 0){
                let objProds = arreglo;
                for (let p = 0; p < objProds.length; p++) {
                    let idDetalle = objProds[p].iddetalle_venta;

                    preciov_tempo = numeral(objProds[p].precio);
                    preciov_tempo = SMONEY+preciov_tempo.format('0,0.00');

                    descuentov_tempo = numeral((objProds[p].precio*objProds[p].cantidad)*objProds[p].descuento);
                    descuentov_tempo = SMONEY+descuentov_tempo.format('0,0.00');

                    total_tempo = numeral((objProds[p].precio*objProds[p].cantidad)-((objProds[p].precio*objProds[p].cantidad)*objProds[p].descuento));
                    total_tempo = SMONEY+total_tempo.format('0,0.00');


                    htmlProds +=`<tr class="odd" role="row">
                        <td><div class="text-center"><button class="btn btn-danger btn-sm" type="button" onClick="fntBorrarDetalleVenta(${idDetalle});"><i class="far fa-trash-alt"></i></button></div></td>
                        <td>${objProds[p].marca} ${objProds[p].nombre}</td>
                        <td align="center"><img src="${base_url}Assets/images/uploads/${objProds[p].img}" width="72" height="52"></td>
                        <td align="right">${objProds[p].cantidad}</td>
                        <td align="right">${preciov_tempo}</td>
                        <td align="right">${descuentov_tempo}</td>
                        <td align="right">${total_tempo}</td>
                        </tr>`;
                    desc       = parseFloat((objProds[p].precio*objProds[p].cantidad)*objProds[p].descuento);
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

            htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td></td><td></td><td align="right">Subtotal</td><td align="right">'+suma_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Impuesto</td><td align="right">'+imp_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Descuento</td><td align="right">'+gdescuento_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Total</td><td align="right">'+gtotal_tempo+'</td></tr></tbody></table>';
            document.querySelector("#tableProductos").innerHTML = htmlProds;
            //onClick="fntDelInfo('.$arrData[$i]['idingreso'].')" title="Cancelar orden de compra"
            actualiza_impuesto(arreglo,impp);                   
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
                let htmlProds = '<table id="tablita" class="table table-bordered"><tbody><th class="col-md-1" style="text-align: center">Acción</th><th class="col-md-2" style="text-align: center">Producto</th><th class="col-md-1" style="text-align: center">Imagen</th><th class="col-md-1" style="text-align: center">Cantidad</th><th class="col-md-2" style="text-align: center">Costo</th><th class="col-md-2" style="text-align: center">Descuento</th><th class="col-md-2" style="text-align: center">Total</th>   ';
                let suma = parseFloat(0.0);
                let imp = parseFloat(0.0);
                let gtotal = parseFloat(0.0);
                let desc       = parseFloat(0.0);
                let gdescuento = parseFloat(0.0);

                let idDetalle = 0;
                if(objData.data.productos.length > 0){
                    let objProds = objData.data.productos;
                    for (let p = 0; p < objProds.length; p++) {

                        preciov_tempo = numeral(objProds[p].precio);
                        preciov_tempo = SMONEY+preciov_tempo.format('0,0.00');

                        descuentov_tempo = numeral((objProds[p].precio*objProds[p].cantidad)*objProds[p].descuento);
                        descuentov_tempo = SMONEY+descuentov_tempo.format('0,0.00');

                        total_tempo = numeral((objProds[p].precio*objProds[p].cantidad)-((objProds[p].precio*objProds[p].cantidad)*objProds[p].descuento));
                        total_tempo = SMONEY+total_tempo.format('0,0.00');


                        let idDetalle = objProds[p].iddetalle_venta;
                        htmlProds +=`<tr class="odd" role="row">
                                            <td><div class="text-center"><button class="btn btn-danger btn-sm" type="button" onClick="fntBorrarDetalleVenta(${idDetalle});"><i class="far fa-trash-alt"></i></button></div></td>
                                            <td>${objProds[p].marca} ${objProds[p].nombre}</td>
                                            <td><img src="${base_url}Assets/images/uploads/${objProds[p].img}" width="72" height="52"></td>
                                            <td align="right">${objProds[p].cantidad}</td>
                                            <td align="right">${preciov_tempo}</td>
                                            <td align="right">${descuentov_tempo}</td>
                                            <td align="right">${total_tempo}</td>
                                        </tr>`;
                        
                        desc       = parseFloat((objProds[p].precio*objProds[p].cantidad)*objProds[p].descuento);
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



                htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td></td><td></td><td align="right">Subtotal</td><td align="right">'+suma_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Impuesto</td><td align="right">'+imp_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Descuento</td><td align="right">'+gdescuento_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td></td><td align="right">Total</td><td align="right">'+gtotal_tempo+'</td></tr></tbody></table>';
                document.querySelector("#tableProductos").innerHTML = htmlProds;
                //onClick="fntDelInfo('.$arrData[$i]['idingreso'].')" title="Cancelar orden de compra"
                actualiza_impuesto(arreglo,objData.data.impuesto);
                
            }
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
        }
    }
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

    for (let p = 0; p < arreglo.length; p++) {
        descuento = descuento + parseFloat((parseFloat(arreglo[p].cantidad)*parseFloat(arreglo[p].precio))*parseFloat(arreglo[p].descuento));
        suma_subtotal = suma_subtotal+((parseFloat(arreglo[p].cantidad)*parseFloat(arreglo[p].precio))  - (parseFloat((parseFloat(arreglo[p].cantidad)*parseFloat(arreglo[p].precio))*parseFloat(arreglo[p].descuento))) );
        }
    p_impuesto = parseFloat(impuesto);
    p_impuesto = suma_subtotal * p_impuesto;
    g_total = suma_subtotal + p_impuesto;
    
    document.querySelector('#txtSubtotal').value = suma_subtotal.toFixed(2);
    document.querySelector('#txtPimpuesto').value = p_impuesto.toFixed(2);
    document.querySelector('#txtTotal').value = g_total.toFixed(2);
    document.querySelector('#txtDescuento').value = descuento.toFixed(2);
}

function openModal(){
    rowTable = "";
    document.querySelector('#idVenta').value="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "HeaderRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Orden de salida";
    document.querySelector('#formVentas').reset();
    document.querySelector('#FrmProductos').classList.add("notBlock");
    document.querySelector('#tableProductos').classList.add("notBlock");
    $('#modalFormVentas').modal('show');
}

function fntAlertaInventario(){
    let valor = document.querySelector("#listStatus").value ;
    if(valor > 1){
        swal("Atención", "Al finalizar la orden de salida, se actualizara el inventario.", "info");
    }
}

function fntObtieneStock(){
    let prod = document.querySelector("#listProductos").value;
    if(parseInt(prod)>=0){
    //console.log("producto= "+prod)
    let requestOS = (window.XMLHttpRequest) ? new XMLHttpRequest():new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Productos/getProductoV/'+prod;
    requestOS.open("POST",ajaxUrl,true);
    requestOS.send();
    requestOS.onreadystatechange = function(){
        if(requestOS.readyState == 4 && requestOS.status == 200){
            let objDataOS = JSON.parse(requestOS.responseText);
            //console.log(request);
            document.querySelector('#intCantidad').value = objDataOS.data.stock;
            document.querySelector('#intPrecio').value = objDataOS.data.precio;

            //swal("Informacion",objData.data.stock,'info');
        }
    }
    }

    //document.querySelector("#intCantidad").value=prod;
    //swal("Atención",prod,"info");
    //console.log(prod);
    //document.querySelector('#intCantidad').innerHTML = request.stock;

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