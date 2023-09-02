let tableIngresos;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

$(document).on('focusin', function(e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});

document.addEventListener('DOMContentLoaded', function(){

    tableIngresos = $('#tableIngresos').dataTable( {
        "aProcessing:":true,
        "aServerSide":true,
        "language":{
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " "+base_url+"Ingresos/getIngresos",
            "dataSrc":""
        },
        "columns":[
            
            {"data":"nombre_proveedor"},
            {"data":"comprobante"},
            {"data":"impuesto"},
            {"data":"total"},
            {"data":"pimpuesto"},
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
    if(document.querySelector("#formIngresos")){
        let formIngresos = document.querySelector('#formIngresos');
        formIngresos.onsubmit = function(e){
            e.preventDefault();

            
            let intIdProveedor = document.querySelector("#listProveedor").selectedOptions[0].text;
            let strComprobante = document.querySelector('#txtComprobante').value;
            let strImpuesto = document.querySelector('#txtImpuesto').value;

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
            
            //console.log(document.querySelector('#txtNotas').value);

            if(intIdProveedor == '' || strComprobante == '' || strImpuesto == ''){
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
            let ajaxUrl = base_url+'Ingresos/setIngreso';
            let formData = new FormData(formIngresos);
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
                            // Si es un usuario nuevo, refresca la tabla y manda a la primera pagina
                            tableIngresos.api().ajax.reload();    
                        }else{

                            // Si es edicion, se mantiene en la pagina actual y solo modifica el renglon.
                            rowTable.cells[0].textContent = intIdProveedor;
                            rowTable.cells[1].textContent = strComprobante;
                            rowTable.cells[2].textContent = strImpuesto;
                            rowTable.cells[3].textContent = SMONEY+sub;
                            rowTable.cells[4].textContent = SMONEY+pimp;
                            rowTable.cells[5].textContent = SMONEY+gtotal;
                            rowTable.cells[6].innerHTML = status;
                            rowTable = "";
                            
                        }
                        $('#modalFormIngresos').modal("hide");
                        formIngresos.reset();
                        swal("Ordenes de ingreso", objData.msg, "success");
                        
                    }else{
                        swal("Error", objData.msg, "error");
                    }
                }
                divLoading.style.display = "none";

                return false;
            }

        }
    }
    
    fntProveedores();
    fntProductos();
},false);

function fntProveedores(){
    if(document.querySelector('#listProveedor')){
        let ajaxUrl = base_url+'Proveedores/getSelectProveedores';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                //debugger;
                //console.log(request);
                document.querySelector('#listProveedor').innerHTML = request.responseText;
                //document.querySelector('#listRolid').value=1; 
                $('#listProveedor').selectpicker('render');      
            }
        }
    }
}

function fntProductos(){
    if(document.querySelector('#listProductos')){
        let ajaxUrl = base_url+'Productos/getSelectProductos';
        let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#listProductos').innerHTML = request.responseText;
                $('#listProductos').selectpicker('render');
            }
        }
    }
}


function fntViewInfo(ingreso){
    let idingreso = ingreso; 
    //console.log(idpersona);
    let SMONEY = "MXN "; //Simbolo de moneda.
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Ingresos/getIngreso/'+idingreso;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){ 

                let estadoIngreso = objData.data.status;
                let htmlProds = '<table class="table table-bordered"><tbody><th class="col-md-4" style="text-align: center">Producto</th><th class="col-md-1" style="text-align: center">Imagen</th><th class="col-md-2" style="text-align: center">Cantidad</th><th class="col-md-2" style="text-align: center">Costo Proveedor</th><th class="col-md-2" style="text-align: center">Total</th>   ';
                let suma = parseFloat(0.0);
                let imp = parseFloat(0.0);
                let gtotal = parseFloat(0.0);
                switch (estadoIngreso) {
                    case "1":
                      estadoIngreso = '<span class="badge badge-success">Activa</span>';
                      break;
                    case "2":
                        estadoIngreso = '<span class="badge badge-info">Finalizada</span>';
                      break;
                    case "0":
                        estadoIngreso = '<span class="badge badge-danger">Cancelada</span>';
                      break;
                  }

                document.querySelector("#celComprobante").innerHTML = objData.data.comprobante;
                document.querySelector("#celNombreProveedor").innerHTML = objData.data.nombre_proveedor;
                document.querySelector("#celImpuesto").innerHTML = objData.data.impuesto;
                document.querySelector("#celTotal").innerHTML = SMONEY+objData.data.total;
                document.querySelector("#celPimpuesto").innerHTML = SMONEY+objData.data.pimpuesto;
                document.querySelector("#celGrantotal").innerHTML = SMONEY+objData.data.grantotal;
                document.querySelector("#celStatus").innerHTML = estadoIngreso;
                document.querySelector("#celNotas").innerHTML = objData.data.notas;
                document.querySelector("#celFechaRegistro").innerHTML = objData.data.created_at;

                if(objData.data.productos.length > 0){
                    let objProds = objData.data.productos;
                    let precioc_tempo = 0;
                    let total_tempo = 0;


                    for (let p = 0; p < objProds.length; p++) {
                        precioc_tempo = numeral(objProds[p].precioc);
                        precioc_tempo = SMONEY+precioc_tempo.format('0,0.00');

                        total_tempo = numeral(objProds[p].precioc*objProds[p].cantidad);
                        total_tempo = SMONEY+total_tempo.format('0,0.00');

                        htmlProds +=`<tr class="odd" role="row">
                                            <td>${objProds[p].marca} ${objProds[p].nombre}</td>
                                            <td><img src="${base_url}Assets/images/uploads/${objProds[p].img}" width="72" height="52"></td>
                                            <td align="right">${objProds[p].cantidad}</td>
                                            <td align="right">${precioc_tempo}</td>
                                            <td align="right">${total_tempo}</td>
                                        </tr>`;
                        suma = parseFloat(suma) + parseFloat(objProds[p].precioc*objProds[p].cantidad);
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

                


                htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td align="right">Subtotal</td><td align="right">'+suma_format+'</td></tr><tr><td></td><td></td><td></td><td align="right">Impuesto</td><td align="right">'+imp_format+'</td></tr><tr><td></td><td></td><td></td><td align="right">Total</td><td align="right">'+gtotal_format+'</td></tr></tbody></table>';
                document.querySelector("#celProds").innerHTML = htmlProds;

                $('#modalViewIngreso').modal('show');

            }else{
                swal("Error",objData.msg,'error');
            }
        }
    }
}

function fntDetalleIngreso(){
    if(document.querySelector("#formIngresos")){
        
        let formIngresos = document.querySelector('#formIngresos');
        let ing = document.querySelector('#idIngreso').value;
        let impp = parseFloat(document.querySelector('#txtImpuesto').value);
        let prod = document.querySelector('#listProductos').value;
        let cant = document.querySelector('#intCantidad').value;
        let prec = document.querySelector('#intPrecioc').value;
        


        //console.log(ing);

        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'Ingresos/setDetalleIngreso';
        let formData = new FormData(formIngresos);
        request.open('POST',ajaxUrl, true);
        request.send(formData);

        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                let objData = JSON.parse(request.responseText);
                //console.log(objData);
                obtener_tabla(ing, impp);

            }

        };
                    
    }

}

function obtener_tabla(ing, impp){
    let SMONEY = "MXN "; //Simbolo de moneda.
    let request2 = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl2 = base_url+'Ingresos/getIngreso/'+ing;
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

            let htmlProds = '<table id="tablita" class="table table-bordered"><tbody><th class="col-md-1" style="text-align: center">Acción</th><th class="col-md-4" style="text-align: center">Producto</th><th class="col-md-1" style="text-align: center">Imagen</th><th class="col-md-1" style="text-align: center">Cantidad</th><th class="col-md-2" style="text-align: center">Costo Proveedor</th><th class="col-md-2" style="text-align: center">Total</th>   ';
            let suma = parseFloat(0.0);
            let imp = parseFloat(0.0);
            let gtotal = parseFloat(0.0);
            let idDetalle = 0;
            if(arreglo.length > 0){
                let objProds = arreglo;
                for (let p = 0; p < objProds.length; p++) {
                    let idDetalle = objProds[p].iddetalle_ingreso;

                    precioc_tempo = numeral(objProds[p].precioc);
                    precioc_tempo = SMONEY+precioc_tempo.format('0,0.00');

                    total_tempo = numeral(objProds[p].precioc*objProds[p].cantidad);
                    total_tempo = SMONEY+total_tempo.format('0,0.00');

                    htmlProds +=`<tr class="odd" role="row">
                        <td><div class="text-center"><button class="btn btn-danger btn-sm" type="button" onClick="fntBorrarDetalleIngreso(${idDetalle});"><i class="far fa-trash-alt"></i></button></div></td>
                        <td>${objProds[p].marca} ${objProds[p].nombre}</td>
                        <td align="center"><img src="${base_url}Assets/images/uploads/${objProds[p].img}" width="72" height="52"></td>
                        <td align="right">${objProds[p].cantidad}</td>
                        <td align="right">${precioc_tempo}</td>
                        <td align="right">${total_tempo}</td>
                        </tr>`;
                    suma = parseFloat(suma) + parseFloat(objProds[p].precioc*objProds[p].cantidad);
                }
            }
            imp = parseFloat(suma) * impp;
            gtotal = suma + imp;

            suma_tempo   = numeral(suma);
            imp_tempo    = numeral(imp);
            gtotal_tempo = numeral(gtotal);

            suma_tempo   = SMONEY+suma_tempo.format('0,0.00');
            imp_tempo    = SMONEY+imp_tempo.format('0,0.00');
            gtotal_tempo = SMONEY+gtotal_tempo.format('0,0.00');

            htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td></td><td align="right">Subtotal</td><td align="right">'+suma_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td align="right">Impuesto</td><td align="right">'+imp_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td align="right">Total</td><td align="right">'+gtotal_tempo+'</td></tr></tbody></table>';
            document.querySelector("#tableProductos").innerHTML = htmlProds;
            //onClick="fntDelInfo('.$arrData[$i]['idingreso'].')" title="Cancelar orden de compra"
            actualiza_impuesto(arreglo,impp);                   
        }
    }
    }  
}

function fntEditInfo(element, ingreso){     

    // Esta variable rowTable, tome todo el valor de la fila de la tabla.  
    rowTable = element.parentNode.parentNode.parentNode;
    //rowTable.cells[1].textContent
    //console.log(rowTable);  
    document.querySelector('#titleModal').innerHTML = "Actualizar Orden de Compra";
    document.querySelector('.modal-header').classList.replace("headerRegister","headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary","btn-info");
    document.querySelector('#btnText').innerHTML= "Actualizar";
    
    let SMONEY = "MXN "; //Simbolo de moneda.
    let idingreso = ingreso;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Ingresos/getIngreso/'+idingreso;
    request.open("GET",ajaxUrl,true);
    request.send();


    request.onreadystatechange = function(){

        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            //console.log(objData);
            if(objData.status){
                document.querySelector('#FrmProductos').classList.remove("notBlock");
                document.querySelector('#tableProductos').classList.remove("notBlock");
                
                document.querySelector('#idIngreso').value = objData.data.idingreso;
                document.querySelector('#listProveedor').value = objData.data.idproveedor;
                document.querySelector('#txtComprobante').value = objData.data.comprobante;
                document.querySelector('#txtImpuesto').value = objData.data.impuesto;
                document.querySelector('#txtSubtotal').value = objData.data.total;
                document.querySelector('#txtPimpuesto').value = objData.data.pimpuesto;
                document.querySelector('#txtTotal').value = objData.data.grantotal;
                document.querySelector('#txtNotas').value = objData.data.notas;
                tinymce.activeEditor.setContent(objData.data.notas); 
                document.querySelector("#listStatus").value = objData.data.status;
                $('#listProveedor').selectpicker('render');
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
                let htmlProds = '<table id="tablita" class="table table-bordered"><tbody><th class="col-md-1" style="text-align: center">Acción</th><th class="col-md-4" style="text-align: center">Producto</th><th class="col-md-1" style="text-align: center">Imagen</th><th class="col-md-1" style="text-align: center">Cantidad</th><th class="col-md-2" style="text-align: center">Costo Proveedor</th><th class="col-md-2" style="text-align: center">Total</th>   ';
                let suma = parseFloat(0.0);
                let imp = parseFloat(0.0);
                let gtotal = parseFloat(0.0);
                let idDetalle = 0;
                if(objData.data.productos.length > 0){
                    let objProds = objData.data.productos;
                    for (let p = 0; p < objProds.length; p++) {

                        precioc_tempo = numeral(objProds[p].precioc);
                        precioc_tempo = SMONEY+precioc_tempo.format('0,0.00');

                        total_tempo = numeral(objProds[p].precioc*objProds[p].cantidad);
                        total_tempo = SMONEY+total_tempo.format('0,0.00');

                        let idDetalle = objProds[p].iddetalle_ingreso;
                        htmlProds +=`<tr class="odd" role="row">
                                            <td><div class="text-center"><button class="btn btn-danger btn-sm" type="button" onClick="fntBorrarDetalleIngreso(${idDetalle});"><i class="far fa-trash-alt"></i></button></div></td>
                                            <td>${objProds[p].marca} ${objProds[p].nombre}</td>
                                            <td><img src="${base_url}Assets/images/uploads/${objProds[p].img}" width="72" height="52"></td>
                                            <td align="right">${objProds[p].cantidad}</td>
                                            <td align="right">${precioc_tempo}</td>
                                            <td align="right">${total_tempo}</td>
                                        </tr>`;
                        suma = parseFloat(suma) + parseFloat(objProds[p].precioc*objProds[p].cantidad);
                    }
                }
                imp = parseFloat(suma) * parseFloat(objData.data.impuesto);
                gtotal = suma + imp;

                suma_tempo   = numeral(suma);
                imp_tempo    = numeral(imp);
                gtotal_tempo = numeral(gtotal);

                suma_tempo   = SMONEY+suma_tempo.format('0,0.00');
                imp_tempo    = SMONEY+imp_tempo.format('0,0.00');
                gtotal_tempo = SMONEY+gtotal_tempo.format('0,0.00');


                htmlProds=htmlProds+'<tr><td></td><td></td><td></td><td></td><td align="right">Subtotal</td><td align="right">'+suma_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td align="right">Impuesto</td><td align="right">'+imp_tempo+'</td></tr><tr><td></td><td></td><td></td><td></td><td align="right">Total</td><td align="right">'+gtotal_tempo+'</td></tr></tbody></table>';
                document.querySelector("#tableProductos").innerHTML = htmlProds;
                //onClick="fntDelInfo('.$arrData[$i]['idingreso'].')" title="Cancelar orden de compra"
                actualiza_impuesto(arreglo,objData.data.impuesto);
                
            }
        }
        $('#modalFormIngresos').modal('show');
    }
}

function fntBorrarDetalleIngreso(idDetalleIngreso){
    let idDetalle = idDetalleIngreso;
    let ing = document.querySelector('#idIngreso').value;
    let impp = parseFloat(document.querySelector('#txtImpuesto').value);
    //alert("Ingreso: "+ing+" Impuesto: "+impp);
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest():new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Ingresos/delDetalleIngreso/'+idDetalle;
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
            obtener_tabla(ing, impp);
        }
    }
}

function fntDelInfo(idingreso){
    let idIngreso = idingreso;
    
    swal({
        title: "Eliminar Orden de Ingreso",
        text: "¿Realmente quieres eliminar la orden de ingreso?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm){
        if (isConfirm){
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest():new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'Ingresos/delIngreso/';
            let strData = "idIngreso="+idIngreso;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Eliminar",objData.msg,"success");
                        tableIngresos.api().ajax.reload();
                    }else{
                        swal("Atención",objData.msg,"error");
                    }
                }
            }
        }
    });
}

function actualiza_impuesto(arreglo, impuesto){
    let suma_subtotal = 0.0;
    let p_impuesto = 0.0;
    let g_total = 0.0;
    for (let p = 0; p < arreglo.length; p++) {
        suma_subtotal = suma_subtotal+(parseFloat(arreglo[p].cantidad)*parseFloat(arreglo[p].precioc));
        }
    p_impuesto = parseFloat(impuesto);
    p_impuesto = suma_subtotal * p_impuesto;
    g_total = suma_subtotal + p_impuesto;
    
    document.querySelector('#txtSubtotal').value = suma_subtotal.toFixed(2);
    document.querySelector('#txtPimpuesto').value = p_impuesto.toFixed(2);
    document.querySelector('#txtTotal').value = g_total.toFixed(2);
}

function openModal(){
    rowTable = "";
    document.querySelector('#idIngreso').value="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "HeaderRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Orden de compra";
    document.querySelector('#formIngresos').reset();
    document.querySelector('#FrmProductos').classList.add("notBlock");
    document.querySelector('#tableProductos').classList.add("notBlock");
    $('#modalFormIngresos').modal('show');
}

function fntAlertaInventario(){
    let valor = document.querySelector("#listStatus").value ;
    if(valor > 1){
        swal("Atención", "Al finalizar la orden de ingreso, se actualizara el inventario.", "info");
    }
}
