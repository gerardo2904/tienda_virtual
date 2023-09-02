
<?php
    //dep($categos);exit;
    if($grafica = "categoMes"){
        $categosMes = $data;
?>
    <script>
        // Build the chart
        Highcharts.chart('categoriasMesAnio', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Ventas por categoría, <?= $categosMes['mes'].' '.$categosMes['anio'] ?> ',
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
                        foreach($categosMes['tiposcatego'] as $categos){
                            echo "{name:'".$categos['nombre']."' ,y:".$categos['grantotal']."}, ";
                        }
                    ?>
                ]
            }]
        });
    </script>

<?php } ?>


<?php
    //dep($ventas);exit;
    if($grafica = "ventasMes"){
        $ventasMes = $data;
?>

<script>
    // Grafica bien del mes
    Highcharts.chart('graficaMes', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Ventas de <?= $ventasMes['mes'].' del '.$ventasMes['anio'] ?>'
        },
        subtitle: {
            text: 'Total ventas <?= SMONEY.'. '.formatMoney($ventasMes['grantotal']) ?>'
        },
        xAxis: {
            categories: [
            <?php
                foreach($ventasMes['ventas'] as $dia){
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
                foreach($ventasMes['ventas'] as $dia){
                echo $dia['grantotal'].",";
                }
            ?>
            ]
        }]
    });
    
</script>

<?php } ?>

<?php
    //dep($ventas);exit;
    if($grafica = "ventasAnio"){
        $ventasAnio = $data;
        //dep($ventasAnio);exit;
?>

<script>
    Highcharts.chart('graficaAnio', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Ventas del año <?= $ventasAnio['anio']?>'
        },
        subtitle: {
            text: 'Se muestra por mes'
        },
        xAxis: {
            categories: [
            <?php
                foreach($ventasAnio['meses'] as $nmeses){
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
                foreach($ventasAnio['meses'] as $mes){
                    echo "['".$mes['mes']."' ,".$mes['grantotal']."],";
                }
            ?>
            ]

        }]
    });

</script>

<?php } ?>
