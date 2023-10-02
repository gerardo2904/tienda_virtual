<?php headerAdmin($data); ?>
    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i><?= $data['page_title']; ?></h1>
          
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>dashboard">Dashboard</a></li>
        </ul>
      </div>
 
      <div class="row">
        <?php if(!empty($_SESSION['permisos'][2]['r'])){ ?>
        <div class="col-md-6 col-lg-3">
          <a href="<?= base_url() ?>Usuarios" class="linkw">
            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
              <div class="info">
                <h4>Usuarios</h4>
                <p><b><?= $data['usuarios']; ?></b></p>
              </div>
            </div>
          </a>
        </div>
        <?php } ?>  
        <div class="col-md-6 col-lg-3">
          <a href="<?= base_url() ?>Clientes" class="linkw">
            <div class="widget-small info coloured-icon"><i class="icon fa fa-id-card fa-3x"></i>
              <div class="info">
                <h4>Clientes</h4>
                <p><b><?= $data['clientes']; ?></b></p>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-6 col-lg-3">
          <a href="<?= base_url() ?>Proveedores" class="linkw">
            <div class="widget-small warning coloured-icon"><i class="icon fa fa-address-book fa-3x"></i>
              <div class="info">
                <h4>Proveedores</h4>
                <p><b><?= $data['proveedores']; ?></b></p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-6 col-lg-3">
          <a href="<?= base_url() ?>Empresas" class="linkw">
            <div class="widget-small danger coloured-icon"><i class="icon fa fa-building fa-3x"></i>
              <div class="info">
                <h4>Empresas</h4>
                <p><b><?= $data['empresas']; ?></b></p>
              </div>
            </div>
          </a>
        </div>
      </div>
      <!-- Segundo renglon del dashboard -->
      <div class="row">
        <div class="col-md-6 col-lg-3">
          <a href="<?= base_url() ?>Categorias" class="linkw">
            <div class="widget-small primary coloured-icon"><i class="icon fa fa-tag fa-3x"></i>
              <div class="info">
                <h4>Categorias</h4>
                <p><b><?= $data['categorias']; ?></b></p>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-6 col-lg-3">
          <a href="<?= base_url() ?>Productos" class="linkw">
            <div class="widget-small info coloured-icon"><i class="icon fa fa-shopping-bag fa-3x"></i>
              <div class="info">
                <h4>Productos</h4>
                <p><b><?= $data['productos']; ?></b></p>
              </div>
            </div>
          </a>
        </div>

        <div class="col-md-6 col-lg-3">
          <a href="<?= base_url() ?>Ingresos" class="linkw">
            <div class="widget-small warning coloured-icon"><i class="icon fa fa-shopping-cart fa-3x"></i>
              <div class="info">
                <h4>Entradas regitradas</h4>
                <p><b><?= $data['ingresos']; ?></b></p>
              </div>
            </div>
          </a>
        </div>
        <?php if(!empty($_SESSION['permisos'][12]['r'])){ ?>
        <div class="col-md-6 col-lg-3">
          <a href="<?= base_url() ?>Ventas" class="linkw">
            <div class="widget-small danger coloured-icon"><i class="icon fa fa-money fa-3x"></i>
              <div class="info">
                <h4>Ordenes de venta</h4>
                <p><b><?= $data['ventas']; ?></b></p>
              </div>
            </div>
          </a>
        </div>
        <?php } ?>
      </div>

      <div class="row">
      <?php if(!empty($_SESSION['permisos'][12]['r'])){ ?>
      <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Ultimas ventas finalizadas</h3>
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>Comprobante</th>
                  <th>Cliente</th>
                  <th>Total</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                
                <?php
                  if(count($data['ultimasVentas']) > 0){
                    foreach($data['ultimasVentas'] as $venta){
                ?>

                <tr>
                  <td><?= $venta['comprobante'] ?></td>
                  <td><?= $venta['nombre_cliente'] ?></td>
                  <td><?= SMONEY." ".$venta['grantotal'] ?></td>
                  <td><a href="<?= base_url() ?>/Ventas/ticket/<?= $venta['idventa'] ?>" Target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                </tr>
                
                <?php } 
                }
                ?>

              </tbody>
            </table>
          </div>
        </div>
        <?php } ?>

        <!-- /* ventas en curso... */  -->
        <?php if(!empty($_SESSION['permisos'][12]['r'])){ ?>
      <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Ultimas ventas No finalizadas</h3>
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>Comprobante</th>
                  <th>Cliente</th>
                  <th>Total</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                
                <?php
                  if(count($data['ultimasVentasNF']) > 0){
                    foreach($data['ultimasVentasNF'] as $ventaNF){
                ?>

                <tr>
                  <td><?= $ventaNF['comprobante'] ?></td>
                  <td><?= $ventaNF['nombre_cliente'] ?></td>
                  <td><?= SMONEY." ".$ventaNF['grantotal'] ?></td>
                  <td><a href="<?= base_url() ?>/Ventas/ticket/<?= $ventaNF['idventa'] ?>" Target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                </tr>
                
                <?php } 
                }
                ?>

              </tbody>
            </table>
          </div>
        </div>
        <?php } ?>
        <!-- /* Fin ventas en curso */ -->

      </div>
      
      <div class="row">
      <div class="col-md-12">
          <div class="tile">
            <div class="container-title">
              <h3 class="tile-title">Categorias por mes y año</h3>
              <div class="dflex">
                <input class="date-picker categoMes" name="categoMes" placeholder="Mes y Año" >     
                <button type="button" class="btnTipoCategoMes btn btn-info btn-sm" onclick="fntSearchCatego();"><i class="fas fa-search"></i></button>
              </div>
            </div>
            <div id="categoriasMesAnio"></div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="container-title">
              <h3 class="tile-title">Ventas por mes</h3>
              <div class="dflex">
                <input class="date-picker ventasMes" name="ventasMes" placeholder="Mes y Año" >     
                <button type="button" class="btnVentasMes btn btn-info btn-sm" onclick="fntSearchMes();"><i class="fas fa-search"></i></button>
              </div>
            </div>
            <div id="graficaMes"></div>
          </div>
        </div>       
        
        <div class="col-md-12">
          <div class="tile">
            <div class="container-title">
              <h3 class="tile-title">Ventas por año</h3>
              <div class="dflex">
                <input class="ventasAnio" name="ventasAnio" placeholder="Año" minlength="4" maxlength="4" onkeypress="return controlTag(event);">     
                <button type="button" class="btnVentasAnio btn btn-info btn-sm" onclick="fntSearchVAnio();"><i class="fas fa-search"></i></button>
              </div>
            </div>
            <div id="graficaAnio"></div>
          </div>
        </div>  

      </div>


    </main>
    <?php footerAdmin($data); ?>    

<script type="text/javascript">
// Data retrieved from https://netmarketshare.com/
// Radialize the colors
Highcharts.setOptions({
    colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: {
                cx: 0.5,
                cy: 0.3,
                r: 0.7
            },
            stops: [
                [0, color],
                [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
            ]
        };
    })
});

// Build the chart
Highcharts.chart('categoriasMesAnio', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Ventas por categoría, <?= $data['categoMes']['mes'].' '.$data['categoMes']['anio'] ?> ',
        align: 'left'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                connectorColor: 'silver'
            }
        }
    },
    series: [{
        name: 'Share',
        data: [
          <?php
              foreach($data['categoMes']['tiposcatego'] as $categos){
                  echo "{name:'".$categos['nombre']."' ,y:".$categos['grantotal']."}, ";
              }
            ?>
        ]
    }]
});



// Grafica bien del mes
Highcharts.chart('graficaMes', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Ventas de <?= $data['ventasMDia']['mes'].' del '.$data['ventasMDia']['anio'] ?>'
    },
    subtitle: {
        text: 'Total ventas <?= SMONEY.'. '.formatMoney($data['ventasMDia']['grantotal']) ?>'
    },
    xAxis: {
        categories: [
          <?php
            foreach($data['ventasMDia']['ventas'] as $dia){
              echo $dia['dia'].",";
            }
          ?>
        ]
    },
    yAxis: {
        title: {
            text: ''
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: '',
        data: [
          <?php
            foreach($data['ventasMDia']['ventas'] as $dia){
              echo $dia['grantotal'].",";
            }
          ?>
        ]
    }]
});








// Graficas de barras...

Highcharts.chart('graficaAnio', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Ventas del año <?= $data['ventasAnio']['anio']?>'
    },
    subtitle: {
        text: 'Se muestra por mes'
    },
    xAxis: {
        categories: [
          <?php
            foreach($data['ventasAnio']['meses'] as $nmeses){
              echo "'".$nmeses['mes']."',";
            }
          ?>
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: '',
        data: [
          <?php
              foreach($data['ventasAnio']['meses'] as $mes){
                echo "['".$mes['mes']."' ,".$mes['grantotal']."],";
              }
          ?>
        ]

    }]
});

</script>