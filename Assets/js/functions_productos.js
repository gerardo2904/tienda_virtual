// Libreria para codigo de barras
document.write(`<script src="${base_url}/Assets/js/plugins/JsBarcode.all.min.js"></script>`);
let tableProductos;
let rowTable = "";


$(document).on('focusin', function(e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});

tableProductos = $('#tableProductos').dataTable( {
    "aProcessing":true,
    "aServerSide":true,
    "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
    },
    "ajax":{
        "url": " "+base_url+"Productos/getProductos",
        "dataSrc":""
    },
    "columns":[
        //{"data":"idproducto"},
        {"data":"codigo"},
        {"data":"marca"},
        {"data":"nombre"},
        {"data":"categoria"},
        {"data":"stock"},
        {"data":"precio_compra"},
        {"data":"precio"},
        {"data":"status"},
        {"data":"img"},
        {"data":"options"}
    ],
    "columnDefs": [
                    { 'className': "textcenter", "targets": [ 2 ] },
                    { 'className': "textright", "targets": [ 3 ] },
                    { 'className': "textcenter", "targets": [ 4 ] },
                    { 'className': "textcenter", "targets": [ 5 ] },
                    { 'className': "textcenter", "targets": [ 6 ] }
                  ],       
    'dom': 'lBfrtip',
    'buttons': [
        {
            "extend": "copyHtml5",
            "text": "<i class='far fa-copy'></i> Copiar",
            "titleAttr":"Copiar",
            "className": "btn btn-secondary",
            "exportOptions": { 
                "columns": [ 0, 1, 2, 3, 4, 5,6] 
            }
        },{
            "extend": "excelHtml5",
            "text": "<i class='fas fa-file-excel'></i> Excel",
            "titleAttr":"Exportar a Excel",
            "className": "btn btn-success",
            "exportOptions": { 
                "columns": [ 0, 1, 2, 3, 4, 5,6] 
            }
        },{
            "extend": "pdfHtml5",
            "text": "<i class='fas fa-file-pdf'></i> PDF",
            "titleAttr":"Exportar a PDF",
            "className": "btn btn-danger",
            "exportOptions": { 
                "columns": [ 0, 1, 2, 3, 4, 5,6] 
            }
        },{
            "extend": "csvHtml5",
            "text": "<i class='fas fa-file-csv'></i> CSV",
            "titleAttr":"Exportar a CSV",
            "className": "btn btn-info",
            "exportOptions": { 
                "columns": [ 0, 1, 2, 3, 4, 5,6] 
            }
        },{
            "extend": "excelHtml5",
            "text": "<i class='fas fa-file-excel'></i> Inventario para etiquetas",
            "titleAttr":"Exportar a Excel",
            "className": "btn btn-success",
            "exportOptions": { 
                "columns": [ 0, 2, 5] 
            }
        }
    ],
    "resonsieve":"true",
    "bDestroy": true,
    "iDisplayLength": 10,
    "order":[[0,"desc"]]  
});


window.addEventListener('load', function() {
    //fntProveedores();
    const opcionCambiada = () => {
        
        $('#listProveedor').selectpicker('render');      
        //console.log("cliente: "+document.formVentas.querySelector('#listCliente').innerHTML);
    };

    $select=document.querySelector("#listProveedor");
    $select.addEventListener("change", opcionCambiada);

    // Si se activa un modal  fntClientes2();

    $('#modalAltaProv').on('hidden.bs.modal', function (event) {
        //alert("si");
        fntProveedores2();
    })


    if(document.querySelector("#formProductos")){
        let formProductos = document.querySelector("#formProductos");
        formProductos.onsubmit = function(e) {
            e.preventDefault();
            
            let intIdProveedor = document.querySelector("#listProveedor").selectedOptions[0].text;

            let strNombre = document.querySelector('#txtNombre').value;
            let strMarca = document.querySelector('#txtMarca').value;
            let intCodigo = document.querySelector('#txtCodigo').value;
            let strPrecio = document.querySelector('#txtPrecio').value;
            let strPrecio_compra = document.querySelector('#txtPrecio_compra').value;
            let intStock = document.querySelector('#txtStock').value;
            let intStatus = document.querySelector('#listStatus').value;
            let strCatego = document.querySelector("#listCategoria").selectedOptions[0].text;

            //fntCodigoMax();
            if(strNombre == '' || intCodigo == '' || strPrecio == '' || intStock == '' )
            {
                swal("Atención", "Todos los campos son obligatorios." , "error");
                return false;
            }
            if(intCodigo.length < 4){
                swal("Atención", "El código debe ser mayor que 4 dígitos." , "error");
                return false;
            }
            divLoading.style.display = "flex";
            tinyMCE.triggerSave();
            let request = (window.XMLHttpRequest) ? 
                            new XMLHttpRequest() : 
                            new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'Productos/setProducto'; 
            let formData = new FormData(formProductos);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        swal("", objData.msg ,"success");
                        document.querySelector("#idProducto").value = objData.idproducto;
                        document.querySelector("#containerGallery").classList.remove("notBlock");

                        if(rowTable == ""){
                            tableProductos.api().ajax.reload();
                        }else{
                           htmlStatus = intStatus == 1 ? 
                            '<span class="badge badge-success">Activo</span>' : 
                            '<span class="badge badge-danger">Inactivo</span>';
                            rowTable.cells[0].textContent = intCodigo;
                            rowTable.cells[1].textContent = strMarca;
                            rowTable.cells[2].textContent = strNombre;
                            rowTable.cells[3].textContent = strCatego;
                            rowTable.cells[4].textContent = intStock;
                            rowTable.cells[5].textContent = smony+strPrecio_compra;
                            rowTable.cells[6].textContent = smony+strPrecio;
                            rowTable.cells[7].innerHTML =  htmlStatus;
                            rowTable = ""; 
                        }
                    }else{
                        swal("Error", objData.msg , "error");
                    }
                }
                divLoading.style.display = "none";
                return false;
            }
        }
    }

    if(document.querySelector("#formProveedor")){
        let formProveedor = document.querySelector('#formProveedor');
        formProveedor.onsubmit = function(e){
            e.preventDefault();
            let strIdentificacion = document.querySelector('#txtIdentificacion').value;
            let strNombre = document.querySelector('#txtNombreP').value;
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
            let ajaxUrl = base_url+'Productos/setProveedor';
            let formData = new FormData(formProveedor);
            request.open('POST',ajaxUrl, true);
            request.send(formData);

            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){  
                        $('#modalAltaProv').modal("hide");
                        formProveedor.reset();
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
    fntCategorias();
    //fntProveedores();

    fntProveedores2();
    fntObtieneRegimen();
    fntObtieneCFDI();
    fntObtieneEstado();
    fntObtieneCiudad();

}, false);

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

function fntProveedores2(){
    //console.log("cliente: "+document.formVentas.querySelector('#listCliente').value);
    if(document.formProductos.querySelector('#listProveedor')){
        let ajaxUrl = base_url+'Proveedores/getSelectProveedores';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.formProductos.querySelector('#listProveedor').innerHTML = request.responseText;
                $('#listProveedor').selectpicker('refresh');
                $('#listProveedor').selectpicker('render');
                
                  
            }
        }
    }
}


if(document.querySelector("#txtCodigo")){
    let inputCodigo = document.querySelector('#txtCodigo');
    inputCodigo.onkeyup = function() {
        if(inputCodigo.value.length >= 4){
            document.querySelector('#divBarCode').classList.remove("notBlock");
            fntBarcode();
        }else{
            document.querySelector('#divBarCode').classList.add("notBlock");
        }
    };
}

tinymce.init({
	selector: '#txtDescripcion',
	width: "100%",
    height: 400,    
    statubar: true,
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table directionality emoticons template paste "
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
});

function fntCodigoMax(){
    if(document.querySelector('#txtCodigo')){
        let ajaxUrl = base_url+'Productos/getMaxCodigoProducto';
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
                    document.querySelector("#txtCodigo").value = objP.codigo;
                    JsBarcode("#barcode", objP.codigo);
                    //console.log(objP.codigo);
                }

            }
        }
    }
}

function fntInputFile(){
    let inputUploadfile = document.querySelectorAll(".inputUploadfile");
    inputUploadfile.forEach(function(inputUploadfile) {
        inputUploadfile.addEventListener('change', function(){
            let idProducto = document.querySelector("#idProducto").value;
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
                    let ajaxUrl = base_url+'Productos/setImage'; 
                    let formData = new FormData();
                    formData.append('idproducto',idProducto);
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
    let idProducto = document.querySelector("#idProducto").value;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Productos/delFile'; 

    let formData = new FormData();
    formData.append('idproducto',idProducto);
    formData.append("file",nameImg);
    request.open("POST",ajaxUrl,true);
    request.send(formData);
    request.onreadystatechange = function(){
        if(request.readyState != 4) return;
        if(request.status == 200){
            let objData = JSON.parse(request.responseText);
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

function fntViewInfo(idProducto){
    let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Productos/getProducto/'+idProducto;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                let htmlImage = "";
                let objProducto = objData.data;
                let estadoProducto = objProducto.status == 1 ? 
                '<span class="badge badge-success">Activo</span>' : 
                '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celCodigo").innerHTML = objProducto.codigo;
                document.querySelector("#celNombre").innerHTML = objProducto.nombre;
                document.querySelector("#celMarca").innerHTML = objProducto.marca;
                document.querySelector("#celPrecio").innerHTML = smony+objProducto.precio;
                document.querySelector("#celPrecio_compra").innerHTML = smony+objProducto.precio_compra;
                document.querySelector("#celStock").innerHTML = objProducto.stock;
                document.querySelector("#celCategoria").innerHTML = objProducto.categoria;
                document.querySelector("#celStatus").innerHTML = estadoProducto;
                document.querySelector("#celDescripcion").innerHTML = objProducto.descripcion;

                if(objProducto.images.length > 0){
                    let objProductos = objProducto.images;
                    for (let p = 0; p < objProductos.length; p++) {
                        htmlImage +=`<img src="${objProductos[p].url_image}"></img>`;
                    }
                }
                document.querySelector("#celFotos").innerHTML = htmlImage;
                $('#modalViewProducto').modal('show');

            }else{
                swal("Error", objData.msg , "error");
            }
        }
    } 
}

function fntEditInfo(element,idProducto){
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').innerHTML ="Actualizar Producto";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";
    let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'Productos/getProducto/'+idProducto;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status)
            {   //console.log(objData);
                let htmlImage = "";
                let objProducto = objData.data;
                document.querySelector("#idProducto").value = objProducto.idproducto;
                document.querySelector("#txtNombre").value = objProducto.nombre;
                document.querySelector("#txtMarca").value = objProducto.marca;
                document.querySelector("#txtDescripcion").value = objProducto.descripcion;
                document.querySelector("#txtCodigo").value = objProducto.codigo;
                document.querySelector("#txtPrecio").value = objProducto.precio;
                document.querySelector("#txtPrecio_compra").value = objProducto.precio_compra;
                document.querySelector("#txtStock").value = objProducto.stock;
                document.querySelector("#listCategoria").value = objProducto.categoriaid;
                document.querySelector("#listStatus").value = objProducto.status;
                document.querySelector('#listProveedor').value = objProducto.idproveedor;
                tinymce.activeEditor.setContent(objProducto.descripcion); 
                $('#listCategoria').selectpicker('render');
                $('#listStatus').selectpicker('render');
                $('#listProveedor').selectpicker('render');
                fntBarcode();

                if(objProducto.images.length > 0){
                    let objProductos = objProducto.images;
                    for (let p = 0; p < objProductos.length; p++) {
                        let key = Date.now()+p;
                        htmlImage +=`<div id="div${key}">
                            <div class="prevImage">
                            <img src="${objProductos[p].url_image}"></img>
                            </div>
                            <button type="button" class="btnDeleteImage" onclick="fntDelItem('#div${key}')" imgname="${objProductos[p].img}">
                            <i class="fas fa-trash-alt"></i></button></div>`;
                    }
                }
                document.querySelector("#containerImages").innerHTML = htmlImage; 
                document.querySelector("#divBarCode").classList.remove("notBlock");
                document.querySelector("#containerGallery").classList.remove("notBlock");           
                $('#modalFormProductos').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

function fntDelInfo(idProducto){
    swal({
        title: "Eliminar Producto",
        text: "¿Realmente quiere eliminar el producto?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        if (isConfirm) 
        {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'Productos/delProducto';
            let strData = "idProducto="+idProducto;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        swal("Eliminar!", objData.msg , "success");
                        tableProductos.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });

}

function fntCategorias(){
    if(document.querySelector('#listCategoria')){
        let ajaxUrl = base_url+'Categorias/getSelectCategorias';
        let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#listCategoria').innerHTML = request.responseText;
                $('#listCategoria').selectpicker('render');
            }
        }
    }
}

function fntBarcode(){
    let codigo = document.querySelector('#txtCodigo').value;
    JsBarcode("#barcode", codigo);
}

function fntPrintBarcode(area){
    let elemntArea = document.querySelector(area);
    let vprint = window.open(' ', 'popimpr', 'width=600, height=400');
    vprint.document.write(elemntArea.innerHTML);
    vprint.document.close();
    vprint.print();
    vprint.close();
}

function openModal(){
    rowTable = "";
    document.querySelector('#idProducto').value="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "HeaderRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Producto";
    document.querySelector('#formProductos').reset();
    document.querySelector("#divBarCode").classList.add("notBlock");
    document.querySelector("#containerGallery").classList.add("notBlock");
    document.querySelector("#containerImages").innerHTML = "";
    $('#modalFormProductos').modal('show');
    document.querySelector("#divBarCode").classList.remove("notBlock");
    if(document.querySelector('#idProducto').value == 0){
        fntCodigoMax();
    }
}
/*
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
*/

function muestraRFC(){
    if (document.querySelector('#fiscales').classList.contains("notBlock")){
        document.querySelector('#fiscales').classList.remove("notBlock");
    }else{
        document.querySelector('#fiscales').classList.add("notBlock");
    }
    //$('#fiscales').fadeToggle();
}

function fntEmailAle(){
    let ajaxUrl = base_url+'Proveedores/getMailAle';
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
                    document.querySelector("#txtNombreP").focus();
                    //$('#txtNombre').focus();
                }

            }
        }
}

function nuevoProveedor(){
    document.querySelector('.modal-header').classList.replace("headerRegister","header-primary");
    $('#modalAltaProv').modal('show');
    fntEmailAle();
}

function fntObtieneRegimen(){
    if(document.querySelector('#listRegimen')){
        let ajaxUrl = base_url+'Proveedores/getSelectRegimen';
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
    if(document.querySelector('#listCFDI')){
        let ajaxUrl = base_url+'Proveedores/getSelectCFDI/'+document.querySelector('#listRegimen').value;
        let requestRe = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        requestRe.open("GET",ajaxUrl,true);
        requestRe.send();
        requestRe.onreadystatechange = function(){
            if(requestRe.readyState == 4 && requestRe.status == 200){
                //document.querySelector('#listCFDI').innerHTML = requestRe.responseText;
                //$('#listCFDI').selectpicker('render');  
                document.querySelector('#listCFDI').innerHTML = '';
                $('#listCFDI').selectpicker('destroy');

                document.querySelector('#listCFDI').innerHTML = requestRe.responseText;
                $('#listCFDI').selectpicker('get');
            }
        }
    }
}

function fntCambioCFDI(){   
    let ajaxUrl = base_url+'Proveedores/getSelectCFDI/'+document.querySelector('#listRegimen').value;
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

function fntObtieneEstado(){
    if(document.querySelector('#listEstado')){
        let ajaxUrl = base_url+'Proveedores/getSelectEstado';
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
        let ajaxUrl = base_url+'Proveedores/getSelectCiudad/'+document.querySelector('#listEstado').value;
        let requestRe = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        requestRe.open("GET",ajaxUrl,true);
        requestRe.send();
        requestRe.onreadystatechange = function(){
            if(requestRe.readyState == 4 && requestRe.status == 200){
                //document.querySelector('#listCiudad').innerHTML = requestRe.responseText;
                //$('#listCiudad').selectpicker('render');  
                document.querySelector('#listCiudad').innerHTML = '';
                $('#listCiudad').selectpicker('destroy');

                document.querySelector('#listCiudad').innerHTML = requestRe.responseText;
                $('#listCiudad').selectpicker('get');
                $('#listCiudad').selectpicker('render');  
            }
        }
    }
}

function fntCambioCiudad(){   
    let ajaxUrl = base_url+'Proveedores/getSelectCiudad/'+document.querySelector('#listEstado').value;
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
