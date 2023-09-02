$('.date-picker').datepicker( {
    closeText: 'Cerrar',
	prevText: '<Ant',
	nextText: 'Sig>',
	currentText: 'Hoy',
	monthNames: ['1 -', '2 -', '3 -', '4 -', '5 -', '6 -', '7 -', '8 -', '9 -', '10 -', '11 -', '12 -'],
	monthNamesShort: ['Enero','Febrero','Marzo','Abril', 'Mayo','Junio','Julio','Agosto','Septiembre', 'Octubre','Noviembre','Diciembre'],
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'MM yy',
    showDays: false,
    onClose: function(dateText, inst) {
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
    }
});


function fntSearchCatego(){
    let fecha = document.querySelector(".categoMes").value;
    if(fecha == ""){
        swal("", "Seleccione Mes y Año","error");
        return false;
    }else{
        let request = (window.XMLHttpRequest) ?
            new XMLHttpRequest():
            new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'Dashboard/tipoCategoMes';
        divLoading.style.display = "flex";
        let formData = new FormData();
        formData.append('fecha',fecha);
        request.open("POST",ajaxUrl,true);
        request.send(formData);

        request.onreadystatechange = function(){
            if(request.readyState !=4) return;
            if(request.status == 200 ) {
                $("#categoriasMesAnio").html(request.responseText);

                divLoading.style.display = "none";
                return false;
            }
        }
    }
}



function fntSearchMes(){
    let fecha = document.querySelector(".ventasMes").value;
    if(fecha == ""){
        swal("", "Seleccione Mes y Año","error");
        return false;
    }else{
        let request = (window.XMLHttpRequest) ?
            new XMLHttpRequest():
            new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'Dashboard/ventasMes';
        divLoading.style.display = "flex";
        let formData = new FormData();
        formData.append('fecha',fecha);
        request.open("POST",ajaxUrl,true);
        request.send(formData);

        request.onreadystatechange = function(){
            if(request.readyState !=4) return;
            if(request.status == 200 ) {
                $("#graficaMes").html(request.responseText);

                divLoading.style.display = "none";
                return false;
            }
        }
    }
}

function fntSearchVAnio(){
    let anio = document.querySelector(".ventasAnio").value;
    if(anio == ""){
        swal("", "Seleccione Año","error");
        return false;
    }else{
        let request = (window.XMLHttpRequest) ?
            new XMLHttpRequest():
            new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'Dashboard/ventasAnio';
        divLoading.style.display = "flex";
        let formData = new FormData();
        formData.append('anio',anio);
        request.open("POST",ajaxUrl,true);
        request.send(formData);

        request.onreadystatechange = function(){
            if(request.readyState !=4) return;
            if(request.status == 200 ) {
                $("#graficaAnio").html(request.responseText);

                divLoading.style.display = "none";
                return false;
            }
        }
    }
}