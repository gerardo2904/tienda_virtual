let tableClientes;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){

    tableClientes = $('#tableClientes').dataTable( {
        "aProcessing:":true,
        "aServerSide":true,
        "language":{
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " "+base_url+"Clientes/getClientes",
            "dataSrc":""
        },
        "columns":[
            //{"data":"idpersona"},
            //{"data":"identificacion"},
            {"data":"nombres"},
            {"data":"apellidos"},
            {"data":"email_user"},
            {"data":"telefono"},
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
            document.querySelector('#listRegimen').value = document.querySelector('#listRegimen').value;
            $('#listRegimen').selectpicker('render');
            

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
                        if(rowTable == ""){
                            // Si es un usuario nuevo, refresca la tabla y manda a la primera pagina
                            tableClientes.api().ajax.reload();    
                        }else{

                            // Si es edicion, se mantiene en la pagina actual y solo modifica el renglon.
                            //rowTable.cells[0].textContent = strIdentificacion;
                            rowTable.cells[0].textContent = strNombre;
                            rowTable.cells[1].textContent = strApellido;
                            rowTable.cells[2].textContent = strEmail;
                            rowTable.cells[3].textContent = intTelefono;
                            rowTable = "";
                        }
                        $('#modalFormCliente').modal("hide");
                        formCliente.reset();
                        swal("Clientes", objData.msg, "success");
                        
                    }else{
                        swal("Error", objData.msg, "error");
                    }
                }
                divLoading.style.display = "none";
                return false;
            }

        }
    }
    

},false);

window.addEventListener('load', function(){
    fntObtieneRegimen();
    fntObtieneCFDI();
    fntObtieneEstado();
    fntObtieneCiudad();
},false);


function fntViewInfo(persona){
    let idpersona = persona; 
    //console.log(idpersona);
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Clientes/getCliente/'+idpersona;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){ 
                //document.querySelector("#cellIdentificacion").innerHTML = objData.data.identificacion;
                document.querySelector("#celNombre").innerHTML = objData.data.nombres;
                document.querySelector("#celApellido").innerHTML = objData.data.apellidos;
                document.querySelector("#celTelefono").innerHTML = objData.data.telefono;
                document.querySelector("#celEmail").innerHTML = objData.data.email_user;
                document.querySelector("#celIde").innerHTML = objData.data.nit;
                document.querySelector("#celNomFiscal").innerHTML = objData.data.nombrefiscal;
                let inte="";
                if(objData.data.numint != ""){
                    inte=", Int. "+objData.data.numint;
                }

                document.querySelector("#celDirFiscal").innerHTML = objData.data.direccionfiscal+', No '+objData.data.numext+inte+'<br>'+objData.data.colonia+'<br>'+'CP '+objData.data.cp+'<BR>'+objData.data.nciudad+', '+objData.data.nestado;
                document.querySelector("#celFechaRegistro").innerHTML = objData.data.fechaRegistro;
                $('#modalViewCliente').modal('show');

            }else{
                swal("Error",objData.msg,'error');
            }
        }
    }
}

function fntEditInfo(element, persona){     
    // Esta variable rowTable, tome todo el valor de la fila de la tabla.  
    rowTable = element.parentNode.parentNode.parentNode;
    //rowTable.cells[1].textContent
    //console.log(rowTable);  
    document.querySelector('#titleModal').innerHTML = "Actualizar Cliente";
    document.querySelector('.modal-header').classList.replace("headerRegister","headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary","btn-info");
    document.querySelector('#btnText').innerHTML= "Actualizar";

    let idpersona = persona;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Clientes/getCliente/'+idpersona;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){

        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){
                document.querySelector("#idUsuario").value = objData.data.idpersona;
                document.querySelector("#txtIdentificacion").value = objData.data.identificacion;
                document.querySelector("#txtNombre").value = objData.data.nombres;
                document.querySelector("#txtApellido").value = objData.data.apellidos;
                document.querySelector("#txtTelefono").value = objData.data.telefono;
                document.querySelector("#txtEmail").value = objData.data.email_user;
                document.querySelector("#txtNit").value = objData.data.nit;
                document.querySelector("#txtNombreFiscal").value = objData.data.nombrefiscal;
                document.querySelector("#txtDirFiscal").value = objData.data.direccionfiscal;
                document.querySelector("#txtNumExt").value = objData.data.numext;
                document.querySelector("#txtNumInt").value = objData.data.numint;
                document.querySelector("#txtColonia").value = objData.data.colonia;
                document.querySelector("#txtCP").value = objData.data.cp;
                document.querySelector("#listRegimen").value = objData.data.regfiscal;
                $('#listRegimen').selectpicker('render');
                document.querySelector("#listCFDI").value = objData.data.usocfdi;
                $('#listCFDI').selectpicker('render');
                document.querySelector("#listEstado").value = objData.data.estado;
                $('#listEstado').selectpicker('render');
                document.querySelector("#listCiudad").value = objData.data.municipio;
                $('#listCiudad').selectpicker('render');

            }
        }
        $('#modalFormCliente').modal('show');
    }
}

function fntDelInfo(idpersona){
    let idUsuario = idpersona;
    
    swal({
        title: "Eliminar Cliente",
        text: "¿Realmente quieres eliminar el Cliente?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm){
        if (isConfirm){
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest():new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'Clientes/delCliente/';
            let strData = "idUsuario="+idUsuario;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Eliminar",objData.msg,"success");
                        tableClientes.api().ajax.reload();
                    }else{
                        swal("Atención",objData.msg,"error");
                    }
                }
        }
       }
    });
}

function muestraRFC(){
    if (document.querySelector('#fiscales').classList.contains("notBlock")){
        document.querySelector('#fiscales').classList.remove("notBlock");
    }else{
        document.querySelector('#fiscales').classList.add("notBlock");
    }
    //$('#fiscales').fadeToggle();
}

function openModal(){
    rowTable = "";
    document.querySelector('#idUsuario').value="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "HeaderRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Cliente";
    document.querySelector('#formCliente').reset();
    $('#modalFormCliente').modal('show');
    fntEmailAle();
    document.querySelector("#txtNombre").focus();
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
                document.querySelector('#listCFDI').innerHTML = requestRe.responseText;
                $('#listCFDI').selectpicker('render');  
            }
        }
    }
}



function fntCambioCFDI(){
    //alert('si');
    
    
    
            
    let ajaxUrl = base_url+'Clientes/getSelectCFDI/'+document.querySelector('#listRegimen').value;
    let requestRe = (window.XMLHttpRequest) ? 
                new XMLHttpRequest() : 
                new ActiveXObject('Microsoft.XMLHTTP');
    requestRe.open("GET",ajaxUrl,true);
    requestRe.send();
    requestRe.onreadystatechange = function(){
        if(requestRe.readyState == 4 && requestRe.status == 200){

            //$('#listCFDI').innerHTML='';
            
            document.querySelector('#listCFDI').innerHTML = '';
            

            document.querySelector('#listCFDI').innerHTML = requestRe.responseText;
            $('#listCFDI').selectpicker('render');  
            $('#listCFDI').reload();
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
                document.querySelector('#listCiudad').innerHTML = requestRe.responseText;
                $('#listCiudad').selectpicker('render');  
            }
        }
    }
}





