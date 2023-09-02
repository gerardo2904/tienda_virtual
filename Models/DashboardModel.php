<?php
    class DashboardModel extends Mysql {

        public function __construct(){
            parent::__construct();
         }


        public function cantUsuarios(){
            $sql="select COUNT(*) as totalUsuarios from persona where status != 0";
            $request = $this->select($sql);
            $total = $request['totalUsuarios'];
            return $total;
        }

        public function cantClientes(){
            $sql="select COUNT(*) as totalClientes from persona where status != 0 AND rolid = ".RCLIENTES;
            $request = $this->select($sql);
            $total = $request['totalClientes'];
            return $total;
        }

        public function cantProveedores(){
            $sql="select COUNT(*) as totalProveedores from persona where status != 0 AND rolid = ".RPROVEEDORES;
            $request = $this->select($sql);
            $total = $request['totalProveedores'];
            return $total;
        }

        public function cantEmpresas(){
            $sql="select COUNT(*) as totalEmpresas from empresas where status = 1 ";
            $request = $this->select($sql);
            $total = $request['totalEmpresas'];
            return $total;
        }

        public function cantCategorias(){
            $sql="select COUNT(*) as totalCategorias from categoria where status != 0 ";
            $request = $this->select($sql);
            $total = $request['totalCategorias'];
            return $total;
        }

        public function cantProductos(){
            $sql="select COUNT(*) as totalProductos from producto where status != 0 and idempresa=".intval($_SESSION['userData']['idempresa']);
            $request = $this->select($sql);
            $total = $request['totalProductos'];
            return $total;
        }

        public function cantIngresos(){
            $sql="select COUNT(*) as totalIngresos from ingreso where status = 2 and idempresa=".intval($_SESSION['userData']['idempresa']);
            $request = $this->select($sql);
            $total = $request['totalIngresos'];
            return $total;
        }

        public function cantVentas(){
            $sql="select COUNT(*) as totalVentas from venta where status = 2 and idempresa=".intval($_SESSION['userData']['idempresa']);
            $request = $this->select($sql);
            $total = $request['totalVentas'];
            return $total;
        }

        public function empresa(){
            $sql="select nombreempresa as nombre from empresas where status = 1 and idempresa=".intval($_SESSION['userData']['idempresa']);
            $request = $this->select($sql);
            $nempresa = $request['nombre'];
            return $nempresa;
        }

        public function ultimasVentas(){
            $intEmpresa = intval($_SESSION['userData']['idempresa']);
			
			$sql="select v.idventa, v.idcliente, v.idpersona, v.comprobante, v.notas, v.status, v.impuesto,
			concat(p.nombres,' ',p.apellidos) as nombre_cliente,
			sum(if(precio=0,0,if(cantidad=0,0,precio*cantidad))) as total,
			sum(if(descuento=0,0,(precio*cantidad)*if(descuento>1,(descuento/100),descuento) )) as sdescuento,
			sum(if(v.impuesto=0,0,(precio*cantidad)*v.impuesto)) as pimpuesto,
			round(sum(if(precio=0,0,(precio*cantidad)+((precio*cantidad)*v.impuesto)-((precio*cantidad)*if(descuento>1,(descuento/100),descuento) )) ),2) as grantotal,
            v.created_at
	 		from venta v
			LEFT JOIN detalle_venta dv 
			ON v.idventa = dv.idventa
			INNER JOIN persona p 
			ON v.idcliente = p.idpersona
			where v.status = 2 AND v.idempresa= $intEmpresa 
			GROUP by idventa order by created_at DESC LIMIT 10";	
	  
			$request = $this->select_all($sql);
			return $request;
        }

        public function selectCategoMes(int $anio, int $mes){
            $intEmpresa = intval($_SESSION['userData']['idempresa']);
            $sql="select  c.nombre, c.idcategoria as catego, sum(dv.cantidad) as pcant,
            round(sum(if(dv.precio=0,0,(dv.precio*dv.cantidad)+((dv.precio*dv.cantidad)*v.impuesto)-((dv.precio*dv.cantidad)*if(dv.descuento>1,(dv.descuento/100),dv.descuento) )) ),2) as grantotal
                 from venta v 
                LEFT JOIN detalle_venta dv 
                    ON v.idventa = dv.idventa
                INNER JOIN producto p 
                    ON dv.idproducto = p.idproducto
                INNER JOIN categoria c
                    ON p.categoriaid = c.idcategoria  
                where v.status = 2 AND v.idempresa= $intEmpresa and MONTH(v.created_at) = $mes and YEAR(v.created_at) = $anio
                GROUP BY catego";
            $categos = $this->select_all($sql);
            $meses = Meses();
            $arrData = array('anio' => $anio, 'mes' => $meses[intval($mes-1)] , 'tiposcatego' => $categos);
            return $arrData;
        }

        public function selectVentasMes(int $anio, int $mes){
            $intEmpresa = intval($_SESSION['userData']['idempresa']);
            $totalVentasMes = 0;
            $arrVentasDias = array();
            $dias = cal_days_in_month(CAL_GREGORIAN,$mes,$anio);
            $n_dia = 1;
            for($i = 0; $i < $dias; $i++){
                $date = date_create($anio.'-'.$mes.'-'.$n_dia);
                $fechaVenta = date_format($date,"Y-m-d");
                
                $sql="select day(v.created_at) as dia , count(v.idventa) as cantidadVentas, 
                             sum(dv.cantidad) as pcant, 
                             round(sum(if(dv.precio=0,0,(dv.precio*dv.cantidad)+((dv.precio*dv.cantidad)*v.impuesto)-((dv.precio*dv.cantidad)*if(dv.descuento>1,(dv.descuento/100),dv.descuento) )) ),2) as grantotal 
                        from venta v 
                        LEFT JOIN detalle_venta dv 
                            ON v.idventa = dv.idventa
                        where v.status = 2 AND v.idempresa= $intEmpresa and DATE(v.created_at) = '$fechaVenta' 
                        group by dia";
                $ventaDia = $this->select($sql);
                $ventaDia['dia'] = $n_dia;
                $ventaDia['grantotal'] = $ventaDia['grantotal'] == "" ? 0:$ventaDia['grantotal'];
                $ventaDia['cantidadVentas'] = $ventaDia['cantidadVentas'] == "" ? 0:$ventaDia['cantidadVentas'];
                $ventaDia['pcant'] = $ventaDia['pcant'] == "" ? 0:$ventaDia['pcant'];
                $totalVentasMes = $totalVentasMes + $ventaDia['grantotal'] ;
                array_push($arrVentasDias, $ventaDia);
                $n_dia++;
            }

            $meses = Meses();
            $arrData = array('anio' => $anio, 'mes' => $meses[intval($mes-1)] , 'grantotal' => $totalVentasMes, 'ventas' => $arrVentasDias);
            return $arrData;

            
            //$categos = $this->select_all($sql);
        }

        public function selectVentasAnio(int $anio){
            $intEmpresa = intval($_SESSION['userData']['idempresa']);
            $arrMVentas = array();
            $arrMeses = Meses();
            for($i=1; $i<=12; $i++){
                $arrData = array('anio' => '', 'no_mes' => '', 'mes' => '', 'grantotal' => '');
            

            $sql="select $anio as anio, $i as mes , count(v.idventa) as cantidadVentas, sum(dv.cantidad) as pcant,
            round(sum(if(dv.precio=0,0,(dv.precio*dv.cantidad)+((dv.precio*dv.cantidad)*v.impuesto)-((dv.precio*dv.cantidad)*if(dv.descuento>1,(dv.descuento/100),dv.descuento) )) ),2) as grantotal
                 from venta v 
                LEFT JOIN detalle_venta dv 
                    ON v.idventa = dv.idventa
                where v.status = 2 AND v.idempresa= $intEmpresa and YEAR(v.created_at) = $anio AND MONTH(v.created_at) = $i 
                group by mes";
            //dep($sql);exit;
            $ventaMes = $this->select($sql);
            $arrData['mes'] = $arrMeses[$i-1];
            if(empty($ventaMes)){
                $arrData['anio'] = $anio;
                $arrData['no_mes'] = $i;
                $arrData['grantotal'] = 0;
            }else{
                $arrData['anio'] = $ventaMes['anio'];
                $arrData['no_mes'] = $ventaMes['mes'];
                $arrData['grantotal'] = $ventaMes['grantotal'];
            }
            array_push($arrMVentas, $arrData);
            }
            //dep($arrMVentas);
            $arrVentas = array('anio' => $anio, 'meses' => $arrMVentas);
            return $arrVentas;
        }
    }

?>