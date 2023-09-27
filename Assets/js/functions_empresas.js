let tableEmpresas;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){

    tableEmpresas = $('#tableEmpresas').dataTable( {
        "aProcessing:":true,
        "aServerSide":true,
        "language":{
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " "+base_url+"Empresas/getEmpresas",
            "dataSrc":""
        },
        "columns":[
            //{"data":"idpersona"},
            {"data":"nombreempresa"},
            {"data":"rfcempresa"},
            {"data":"nciudad"},
            {"data":"telempresa"},
            {"data":"emailempresa"},
            {"data":"status"},
            {"data":"img"},
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

    if(document.querySelector("#formEmpresa")){
        let formEmpresa = document.querySelector('#formEmpresa');
        formEmpresa.onsubmit = function(e){
            e.preventDefault();
            let strNombreEmpresa = document.querySelector('#txtNombreEmpresa').value;
            let strRfcEmpresa = document.querySelector('#txtRfcEmpresa').value;
            let strDireccionEmpresa = document.querySelector('#txtDireccionEmpresa').value;
            let strCiudadEmpresa = document.querySelector('#listCiudad').value;
            let intCpEmpresa = document.querySelector('#txtCP').value;
            let intTelEmpresa = document.querySelector('#txtTelEmpresa').value;
            //let intCelEmpresa = document.querySelector('#txtCelEmpresa').value;
            let strEmailEmpresa = document.querySelector('#txtEmailEmpresa').value;
            let intStatus = document.querySelector('#listStatus').value;
            
            if(strNombreEmpresa == '' || strRfcEmpresa == '' || strDireccionEmpresa == '' ||  strCiudadEmpresa == '' || intCpEmpresa == '' || intTelEmpresa == '' || strEmailEmpresa == ''){
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
            let ajaxUrl = base_url+'Empresas/setEmpresa';
            let formData = new FormData(formEmpresa);
            request.open('POST',ajaxUrl, true);
            request.send(formData);

            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        if(rowTable == ""){
                            // Si es una empresa nueva, refresca la tabla y manda a la primera pagina
                            tableEmpresas.api().ajax.reload();    
                        }else{

                            // Si es edicion, se mantiene en la pagina actual y solo modifica el renglon.
                            htmlStatus = intStatus == 1 ? 
                            '<span class="badge badge-success">Activo</span>' : 
                            '<span class="badge badge-danger">Inactivo</span>';
                            rowTable.cells[0].textContent = strNombreEmpresa;
                            rowTable.cells[1].textContent = strRfcEmpresa; 
                            rowTable.cells[2].textContent = strCiudadEmpresa;
                            rowTable.cells[3].textContent = intTelEmpresa;
                            rowTable.cells[4].textContent = strEmailEmpresa;
                            rowTable.cells[5].innerHTML =  htmlStatus;
                            rowTable = "";
                        }
                        $('#modalFormEmpresa').modal("hide");
                        formEmpresa.reset();
                        swal("Empresas", objData.msg, "success");
                        
                    }else{
                        swal("Error", objData.msg, "error");
                    }
                }
                divLoading.style.display = "none";
                return false;
            }

        }
    }

    if(document.querySelector(".btnAddImage")){
        let btnAddImage =  document.querySelector(".btnAddImage");
        btnAddImage.onclick = function(e){
         let key = Date.now();
         let newElement = document.createElement("div");
         newElement.id= "div"+key;
         newElement.innerHTML = `
             <div class="prevImage"></div>
             <input type="file" name="foto" id="img${key}" class="inputUploadfile">
             <label for="img${key}" class="btnUploadfile"><i class="fas fa-upload "></i></label>
             <button class="btnDeleteImage notBlock" type="button" onclick="fntDelItem('#div${key}')"><i class="fas fa-trash-alt"></i></button>`;
         document.querySelector("#containerImages").appendChild(newElement);
         document.querySelector("#div"+key+" .btnUploadfile").click();
         fntInputFile();
        }
     }
     fntInputFile();
},false);

window.addEventListener('load', function(){
    fntObtieneRegimen();
    fntObtieneEstado();
    fntObtieneCiudad();
},false);


function fntInputFile(){
    let inputUploadfile = document.querySelectorAll(".inputUploadfile");
    inputUploadfile.forEach(function(inputUploadfile) {
        inputUploadfile.addEventListener('change', function(){
            let idEmpresa = document.querySelector("#idEmpresa").value;
            let parentId = this.parentNode.getAttribute("id");
            let idFile = this.getAttribute("id");            
            let uploadFoto = document.querySelector("#"+idFile).value;
            let fileimg = document.querySelector("#"+idFile).files;
            let prevImg = document.querySelector("#"+parentId+" .prevImage");
            let nav = window.URL || window.webkitURL;
            if(uploadFoto !=''){
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png'){
                    prevImg.innerHTML = "Archivo no válido";
                    uploadFoto.value = "";
                    return false;
                }else{
                    let objeto_url = nav.createObjectURL(this.files[0]);
                    prevImg.innerHTML = `<img class="loading" src="${base_url}/Assets/images/loading.svg" >`;

                    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                    let ajaxUrl = base_url+'Empresas/setImage'; 
                    let formData = new FormData();
                    formData.append('idEmpresa',idEmpresa);
                    formData.append("foto", this.files[0]);
                    request.open("POST",ajaxUrl,true);
                    request.send(formData);
                    request.onreadystatechange = function(){
                        if(request.readyState != 4) return;
                        if(request.status == 200){
                            let objData = JSON.parse(request.responseText);
                            if(objData.status){
                                prevImg.innerHTML = `<img src="${objeto_url}">`;
                                document.querySelector("#"+parentId+" .btnDeleteImage").setAttribute("imgname",objData.imgname);
                                document.querySelector("#"+parentId+" .btnUploadfile").classList.add("notBlock");
                                document.querySelector("#"+parentId+" .btnDeleteImage").classList.remove("notBlock");
                            }else{
                                swal("Error", objData.msg , "error");
                            }
                        }
                    }

                }
            }

        });
    });
}

function fntDelItem(element){
    let nameImg = document.querySelector(element+' .btnDeleteImage').getAttribute("imgname");
    let idEmpresa = document.querySelector("#idEmpresa").value;
    //console.log(idEmpresa);
    //console.log(nameImg);
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Empresas/delFile'; 

    let formData = new FormData();
    
    formData.append('idempresa',idEmpresa);
    formData.append("file",nameImg);
    
    request.open("POST",ajaxUrl,true);
    request.send(formData);
    request.onreadystatechange = function(){
        if(request.readyState != 4) return;
        if(request.status == 200){
            let objData = JSON.parse(request.responseText);
            console.log(objData);
            if(objData.status)
            {
                let itemRemove = document.querySelector(element);
                itemRemove.parentNode.removeChild(itemRemove);
            }else{
                swal("", objData.msg , "error");
            }
        }
    }
}


function fntViewInfo(empresa){
    let idempresa = empresa; 
    //console.log(idpersona);
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Empresas/getEmpresa/'+idempresa;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status){ 
                let htmlImage = "";
                let objEmpresa = objData.data;
                let estadoEmpresa = objEmpresa.status == 1 ? 
                '<span class="badge badge-success">Activo</span>' : 
                '<span class="badge badge-danger">Inactivo</span>';
                //console.log(objEmpresa);

                let nint="";
                if(objData.data.numint != ""){
                    nint=", Int. "+objData.data.numint;
                }

                document.querySelector("#celNombreEmpresa").innerHTML = objEmpresa.nombreempresa;
                document.querySelector("#celRfcEmpresa").innerHTML = objEmpresa.rfcempresa;
                document.querySelector("#celDireccionEmpresa").innerHTML = objEmpresa.direccionempresa+', No. '+objEmpresa.numext+nint;
                document.querySelector("#celColoniaEmpresa").innerHTML = objEmpresa.colonia;
                document.querySelector("#celCiudadEmpresa").innerHTML = objEmpresa.nciudad+', '+objEmpresa.nciudad;
                document.querySelector("#celCpEmpresa").innerHTML = objEmpresa.cpempresa;
                document.querySelector("#celTelEmpresa").innerHTML = objEmpresa.telempresa;
                document.querySelector("#celEmailEmpresa").innerHTML = objEmpresa.emailempresa;
                document.querySelector("#celStatus").innerHTML = estadoEmpresa;
                document.querySelector("#celFechaRegistro").innerHTML = objEmpresa.created_at;

                
                if(objEmpresa.images.length > 0){
                    let objEmpresas = objEmpresa.images;
                    for (let p = 0; p < objEmpresas.length; p++) {
                        htmlImage +=`<img src="${objEmpresas[p].url_image}"></img>`;
                    }
                }
                document.querySelector("#celFotos").innerHTML = htmlImage;
                
                $('#modalViewEmpresa').modal('show');

            }else{
                swal("Error",objData.msg,'error');
            }
        }
    }
}

function fntEditInfo(element, empresa){     
    // Esta variable rowTable, tome todo el valor de la fila de la tabla.  
    rowTable = element.parentNode.parentNode.parentNode;
    //rowTable.cells[1].textContent
    //console.log(rowTable);  
    document.querySelector('#titleModal').innerHTML = "Actualizar Empresa";
    document.querySelector('.modal-header').classList.replace("headerRegister","headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary","btn-info");
    document.querySelector('#btnText').innerHTML= "Actualizar";

    let idempresa = empresa;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Empresas/getEmpresa/'+idempresa;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){

        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            //console.log(objData);

            if(objData.status){
                let htmlImage = "";
                document.querySelector("#idEmpresa").value = objData.data.idempresa;
                document.querySelector("#txtNombreEmpresa").value = objData.data.nombreempresa;
                document.querySelector("#txtRfcEmpresa").value = objData.data.rfcempresa;
                document.querySelector("#txtDireccionEmpresa").value = objData.data.direccionempresa;
                document.querySelector("#txtTelEmpresa").value = objData.data.telempresa;
                document.querySelector("#txtCelEmpresa").value = objData.data.celempresa;
                document.querySelector("#txtEmailEmpresa").value = objData.data.emailempresa;
                document.querySelector("#listStatus").value = objData.data.status;
                //document.querySelector("#txtFechaRegistro").innerHTML = objData.data.updated_at;
                document.querySelector("#txtNumExt").value = objData.data.numext;
                document.querySelector("#txtNumInt").value = objData.data.numint;
                document.querySelector("#txtColonia").value = objData.data.colonia;
                document.querySelector("#txtCP").value = objData.data.cpempresa;
                document.querySelector("#listRegimen").value = objData.data.regfiscal;
                $('#listRegimen').selectpicker('render');
                document.querySelector("#listEstado").value = objData.data.estado;
                $('#listEstado').selectpicker('render');
                document.querySelector("#listCiudad").value = objData.data.ciudadempresa;
                $('#listCiudad').selectpicker('render');


                if(objData.data.images.length > 0){
                    let objEmpresas = objData.data.images;
                    for (let p = 0; p < objEmpresas.length; p++) {
                        let key = Date.now()+p;
                        htmlImage +=`<div id="div${key}">
                            <div class="prevImage">
                            <img src="${objEmpresas[p].url_image}"></img>
                            </div>
                            <button type="button" class="btnDeleteImage" onclick="fntDelItem('#div${key}')" imgname="${objEmpresas[p].img}">
                            <i class="fas fa-trash-alt"></i></button></div>`;
                    }
                }
                document.querySelector("#containerImages").innerHTML = htmlImage; 
                document.querySelector("#containerGallery").classList.remove("notBlock");  

            }
        }
        $('#modalFormEmpresa').modal('show');
    }
}

function fntDelInfo(idempresa){
    let idEmpresa = idempresa;
    
    swal({
        title: "Eliminar Empresa",
        text: "¿Realmente quieres eliminar la Empresa?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm){
        if (isConfirm){
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest():new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'Empresas/delEmpresa/';
            let strData = "idEmpresa="+idEmpresa;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Eliminar",objData.msg,"success");
                        tableEmpresas.api().ajax.reload();
                    }else{
                        swal("Atención",objData.msg,"error");
                    }
                }
        }
       }
    });
}


function openModal(){
    rowTable = "";
    document.querySelector('#idEmpresa').value="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "HeaderRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Empresa";
    document.querySelector('#formEmpresa').reset();
    document.querySelector("#containerGallery").classList.add("notBlock");
    document.querySelector("#containerImages").innerHTML = "";
    $('#modalFormEmpresa').modal('show');
}

function fntObtieneRegimen(){
    if(document.querySelector('#listRegimen')){
        let ajaxUrl = base_url+'Empresas/getSelectRegimen';
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

function fntObtieneEstado(){
    if(document.querySelector('#listEstado')){
        let ajaxUrl = base_url+'Empresas/getSelectEstado';
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
        let ajaxUrl = base_url+'Empresas/getSelectCiudad/'+document.querySelector('#listEstado').value;
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

