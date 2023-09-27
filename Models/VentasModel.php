<?php 

	class VentasModel extends Mysql
	{
		private $intIdVenta;
		private $intIdCliente;
		private $intIdPersona; 
		private $strComprobante;
		private $strImpuesto;
		private $intStatus;
		private $strNotas;
		private $intIdDetalleVenta;

        private $intProducto;
        private $intCantidad;
        private $intPrecio;
		private $intEmpresa;
		private $intDescuento;
        //private $strEtiqueta;

		public function __construct()
		{
			parent::__construct();
		}

		public function selectVentas(){
			$intEmpresa = intval($_SESSION['userData']['idempresa']);
			/*$sql = "SELECT i.idventa, i.idcliente, i.idpersona, i.comprobante, i.impuesto,
                concat(p.nombres, ' ', p.apellidos) as nombre_cliente,
                i.notas, i.status,
                (select if(count(precio)=0,0,sum(precio)) from detalle_venta where idventa = i.idventa ) as total,
                (select if(count(precio)=0,0,sum(precio))*i.impuesto from detalle_venta where idventa = i.idventa ) as pimpuesto,
                (select if(count(precio)=0,0,sum(precio))*(1+i.impuesto) from detalle_venta where idventa = i.idventa ) as grantotal
                FROM venta i 
                INNER JOIN persona p
                ON i.idcliente = p.idpersona
                WHERE p.status != 0 AND i.idempresa = ".$intEmpresa; 
			*/
			$sql="select v.idventa, v.idcliente, v.idpersona, v.comprobante, v.notas, v.status, v.impuesto,
			concat(p.nombres,' ',p.apellidos) as nombre_cliente,
			sum(if(precio=0,0,if(cantidad=0,0,precio*cantidad))) as total,
			sum(if(descuento=0,0,(precio*cantidad)*if(descuento>1,(descuento/100),descuento) )) as sdescuento,
			sum(if(v.impuesto=0,0,(precio*cantidad)*v.impuesto)) as pimpuesto,
			sum(if(precio=0,0,(precio*cantidad)+((precio*cantidad)*v.impuesto)-((precio*cantidad)*if(descuento>1,(descuento/100),descuento) )) ) as grantotal
	 		from venta v
			LEFT JOIN detalle_venta dv 
			ON v.idventa = dv.idventa
			INNER JOIN persona p 
			ON v.idcliente = p.idpersona
			where v.status != 0 AND v.idempresa= $intEmpresa 
			GROUP by idventa";	
				
            
			$request = $this->select_all($sql);
			return $request;
		}	

        public function selectDetalleVentas(int $idventa){
            $sql = "SELECT i.iddetalle_venta, i.idventa, i.idproducto, i.cantidad, i.precio,
                p.nombre as nombre_producto
                (i.cantidad*i.precio) as subtotal_producto
                FROM detalle_venta i 
                INNER JOIN producto p
                ON i.idproducto = p.idproducto
                WHERE i.idventa = ".$idventa;
			$request = $this->select_all($sql);
			return $request;
        }

        /*string $ruta, poner antes de status*/
		public function insertVenta(int $idcliente, int $idpersona, string $comprobante, string $impuesto, string $notas, int $status, int $empresa){
			$this->intIdCliente = $idcliente;
            $this->intIdPersona = $idpersona;
            $this->strComprobante = $comprobante;
            $this->strImpuesto = $impuesto;
            $this->strNotas = $notas;
            $this->intStatus = $status;
			$this->intEmpresa = $empresa;

			$return = 0;
			$sql = "SELECT * FROM venta WHERE comprobante = '{$this->strComprobante}'";
			$request = $this->select_all($sql);
			if(empty($request))
			{
                /*ruta,  poner antes de status*/  /*,? poner al final de values*/
				$query_insert  = "INSERT INTO venta(idcliente, idpersona, comprobante, impuesto, notas, status, idempresa) 
								  VALUES(?,?,?,?,?,?,?)";
                //$this->strRuta, poner antes de intStatus
	        	$arrData = array($this->intIdCliente,
        						$this->intIdPersona,
        						$this->strComprobante,
        						$this->strImpuesto,
        						$this->strNotas,
        						$this->intStatus,
							    $this->intEmpresa);
                //dep($query_insert);
                //dep($arrData);
                //die();
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        // string $ruta, poner antes de status
		public function updateVenta(int $idventa, int $idcliente, int $idpersona, string $comprobante, string $impuesto, string $notas, int $status, int $empresa){
			$this->intIdVenta = $idventa;
            $this->intIdCliente = $idcliente;
            $this->intIdPersona = $idpersona;
            $this->strComprobante = $comprobante;
            $this->strImpuesto = $impuesto;
            $this->strNotas = $notas;
            $this->intStatus = $status;
			$this->intEmpresa = $empresa;
            
			//$this->strRuta = $ruta;
			
			$return = 0;
			$sql = "SELECT * FROM venta WHERE comprobante = '{$this->strComprobante}' AND idventa != $this->intIdVenta ";
			$request = $this->select_all($sql);
			if(empty($request))
			{ 
                //ruta=?,
				$sql = "UPDATE venta SET idcliente=?,idpersona=?,comprobante=?,impuesto=?,status=?,notas=?, idempresa=? 
						WHERE idventa = $this->intIdVenta ";
                 // $this->strRuta,
				$arrData = array($this->intIdCliente,
        						 $this->intIdPersona,
        						 $this->strComprobante,
        						 $this->strImpuesto,
        						 $this->intStatus,
        						 $this->strNotas,
								 $this->intEmpresa);

	        	$request = $this->update($sql,$arrData);
	        	$return = $request;

			}else{
				$return = "exist";
			}
	        return $return;
		}

		public function addProducto(int $idproducto, 
									int $codigo, 
									string $precio_venta, 
									int $stock, 
									int $empresa){

			$this->intIdProducto = $idproducto;
			$this->intCodigo = $codigo;
			$this->intPrecio_venta = $precio_venta;
			$this->intStock = $stock;
			$this->intEmpresa = $empresa;
			$return = 0;

			$sql = "SELECT * FROM producto WHERE codigo = '{$this->intCodigo}' AND idproducto = $this->intIdProducto ";
			$request = $this->select_all($sql);
			$cantidad_prod = $request[0]['stock'];
			$cantidad_prod = $cantidad_prod + $this->intStock;
			//dep($cantidad_prod);die();
			if(!empty($request))
			{
                //dep($request);die();
				$sql = "UPDATE producto 
						SET precio_compra=?, stock=?
						WHERE idproducto = $this->intIdProducto AND codigo = $this->intCodigo AND
						      idempresa = $this->intEmpresa";
                 // $this->strRuta,
				$arrData = array($this->intPrecio_venta, $cantidad_prod);
				//dep($sql);dep($arrData);die();

	        	$request = $this->update($sql,$arrData);
	        	$return = $request;
				//dep($return);die();
			}else{
				$return = "no existe";
			}
	        return $return;
		}

		public function actualizaDetalleVenta($iddetalleventa, $porcenta){
            $ven = $iddetalleventa;
			$por=$porcenta;
            //dep($ven);
			//dep($por);die();
            $return = 0;

            $query_update = "UPDATE detalle_venta set descuento=? 
			            WHERE iddetalle_venta = $ven ";

            $arrDataU = array($por);

            $requestU = $this->update($query_update,$arrDataU);
	        $return = $requestU;

            return $return;
        }

        public function updateDetalleVenta($idVenta, $idProducto, $cantidad, $precio, $descuento){
            $this->intIdVenta = $idVenta;
            $this->intProducto  = $idProducto;
            $this->intCantidad  = $cantidad;
            $this->intPrecio   = $precio;
			$this->intDescuento = $descuento;
            //$this->strEtiqueta  = $etiqueta;
            //dep($descuento);die();
            $return = 0;

            //Verificar si hay existencias...
            $sqlp = "SELECT idproducto, idempresa, stock from producto
                    WHERE idproducto = $this->intProducto AND (stock > $this->intCantidad OR stock = $this->intCantidad) ";
            
			$requestp = $this->select($sqlp);

            $cantidad_prod = $requestp['stock'];
            //dep($cantidad_prod);die();

            $restaP = $cantidad_prod - $this->intCantidad;

            if(!empty($requestp)){

                $query_update = "UPDATE producto set stock=? 
                                 WHERE idproducto = $this->intProducto AND (stock > $this->intCantidad OR stock = $this->intCantidad)";

                $arrDataU = array($restaP);

                $requestU = $this->update($query_update,$arrDataU);
	        	$return = $requestU;

                //dep($return);die();

                if($return){
                    $return = 0;
                    $query_insert  = "INSERT INTO detalle_venta(idventa, idproducto, cantidad, precio, descuento) 
								  VALUES(?,?,?,?,?)";
            
	                $arrData = array($this->intIdVenta,
        						$this->intProducto,
        						$this->intCantidad,
        						$this->intPrecio,
							    $this->intDescuento);
            
                    $request_insert = $this->insert($query_insert,$arrData);
	                $return = $request_insert;
                }
            }

            return $return;
        }

		public function selectVenta(int $idventa){
			$this->intIdVenta = $idventa;
			$sql = "SELECT i.idventa, i.idcliente, i.idpersona, i.comprobante, i.impuesto,
			concat(p.nombres, ' ', p.apellidos) as nombre_cliente, p.telefono as telcliente,
			i.notas, i.status, i.idempresa,
			(select if(count(precio)=0,0,sum(precio)) from detalle_venta where idventa = i.idventa ) as total,
			(select sum(if(descuento=0,0,(precio*cantidad)*if(descuento>1,(descuento/100),descuento))) from detalle_venta where idventa = i.idventa ) as sdescuento,
			(select if(count(precio)=0,0,sum(precio))*i.impuesto from detalle_venta where idventa = i.idventa ) as pimpuesto,
			(select if(count(precio)=0,0,((sum(precio))*(1+i.impuesto))) from detalle_venta where idventa = i.idventa ) - (select sum(if(descuento=0,0,(precio*cantidad)*if(descuento>1,(descuento/100),descuento))) from detalle_venta where idventa = i.idventa )as grantotal, 
			i.created_at,
			e.nombreempresa, e.rfcempresa, e.direccionempresa, e.numext, e.numint, e.colonia, e.cpempresa, e.ciudadempresa, e.emailempresa, e.telempresa, e.celempresa,
			(select nombre from estados where estados.id=e.estado) as nestado,        
            (select nombre from municipios where municipios.id=e.ciudadempresa) as nciudad, 
            (select descripcion_reg from rfiscal where rfiscal.id=e.regfiscal) as nregfiscal
			FROM venta i 
			INNER JOIN persona p
			ON i.idcliente = p.idpersona
			INNER JOIN empresas e
			ON i.idempresa = e.idempresa 
			WHERE i.idventa = $this->intIdVenta ";
            /*
			$sql="select v.idventa, v.idcliente, v.idpersona, v.comprobante, v.notas, v.status, v.impuesto,
			concat(p.nombres,' ',p.apellidos) as nombre_cliente,
			sum(if(precio=0,0,if(cantidad=0,0,precio*cantidad))) as total,
			sum(if(descuento=0,0,(precio*cantidad)*if(descuento>1,(descuento/100),descuento) )) as sdescuento,
			sum(if(v.impuesto=0,0,(precio*cantidad)*v.impuesto)) as pimpuesto,
			sum(if(precio=0,0,(precio*cantidad)+((precio*cantidad)*v.impuesto)-((precio*cantidad)*if(descuento>1,(descuento/100),descuento) )) ) as grantotal
	 		from venta v
			LEFT JOIN detalle_venta dv 
			ON v.idventa = dv.idventa
			INNER JOIN persona p 
			ON v.idcliente = p.idpersona
			where v.status != 0 AND v.idempresa= $intEmpresa 
			GROUP by idventa";	
*/
			$request = $this->select($sql);
			return $request;

		}
        
        public function selectProductos(int $idventa){
			$this->intIdVenta = $idventa;
			$sql = "SELECT i.iddetalle_venta, i.idventa, i.idproducto, i.cantidad, i.precio, i.descuento,
                    p.codigo, p.nombre, p.marca, p.descripcion, p.categoriaid,
					(select img from imagen where productoid = i.idproducto limit 1) as img
					FROM detalle_venta i
                    INNER JOIN producto p
                    ON i.idproducto = p.idproducto
					WHERE idventa = $this->intIdVenta";
			$request = $this->select_all($sql);
			return $request;
		}
        
        public function deleteDetalleVenta(int $iddetalleventa){

            // Obtiene detalle venta...
            $sqlv = "SELECT idproducto, cantidad from detalle_venta
                    WHERE iddetalle_venta = $iddetalleventa ";
            
			$requestv = $this->select($sqlv);

            $cantidad_prodv = $requestv['cantidad'];
            $id_producto = $requestv['idproducto'];

            // Obtiene Producto...
            $sqlp = "SELECT idproducto, idempresa, stock from producto
                    WHERE idproducto = $id_producto ";
            
			$requestp = $this->select($sqlp);

            $cantidad_prod = $requestp['stock'];

            $suma = $cantidad_prodv + $cantidad_prod;

            //idproducto
            $query_update = "UPDATE producto set stock=? 
                                 WHERE idproducto = $id_producto ";

            $arrDataU = array($suma);

            $requestU = $this->update($query_update,$arrDataU);
	        $return = $requestU;


			$sql = "DELETE FROM detalle_venta WHERE iddetalle_venta = $iddetalleventa";
			$request_delete = $this->delete($sql);
	        return $request_delete;
        }

        public function deleteVenta(int $idventa){
			$this->intIdVenta = $idventa;
			//$sql = "UPDATE ingreso SET status = ? WHERE idingreso = $this->intIdIngreso ";
            //$arrData = array(0);
			//$request = $this->update($sql,$arrData);
            
            $sql_sel = "SELECT iddetalle_venta FROM detalle_venta WHERE idventa = $this->intIdVenta";
            $request_sel = $this->select_all($sql_sel);
            if(empty($request_sel)){
                $sql="DELETE FROM venta WHERE idventa = $this->intIdVenta";   
                $request = $this->delete($sql);    
            }else{
				$arrDataD = $request_sel;
				//dep($arrDataD[0]['iddetalle_venta']);die();

				
				
				for ($i=0; $i < count($arrDataD); $i++) {
					//deleteDetalleVenta($arrDataD[$i]['iddetalle_venta']);
					//************************************************** */
						// Obtiene detalle venta...
						$sqlv = "SELECT idproducto, cantidad from detalle_venta
						WHERE iddetalle_venta = ".$arrDataD[$i]['iddetalle_venta'];
				
						$requestv = $this->select($sqlv);
			
						$cantidad_prodv = $requestv['cantidad'];
						$id_producto = $requestv['idproducto'];
			
						// Obtiene Producto...
						$sqlp = "SELECT idproducto, idempresa, stock from producto
								WHERE idproducto = $id_producto ";
						
						$requestp = $this->select($sqlp);
			
						$cantidad_prod = $requestp['stock'];
			
						$suma = $cantidad_prodv + $cantidad_prod;
			
						//idproducto
						$query_update = "UPDATE producto set stock=? 
											WHERE idproducto = $id_producto ";
			
						$arrDataU = array($suma);
			
						$requestU = $this->update($query_update,$arrDataU);
						//$return = $requestU;
			
			
						$sql = "DELETE FROM detalle_venta WHERE iddetalle_venta = ".$arrDataD[$i]['iddetalle_venta'];
						$request_delete = $this->delete($sql);
						//return $request_delete;
					//************************************************** */
				}
				

                $sql="DELETE venta,detalle_venta FROM venta
                            INNER JOIN
                            detalle_venta ON detalle_venta.idventa = venta.idventa 
                            WHERE
                            venta.idventa = $this->intIdVenta";   
			
                $request = $this->delete($sql);    
            }
			return $request;
		}

		public function getComprobanteMax(){
			$intEmpresa = intval($_SESSION['userData']['idempresa']);
			$sql = "SELECT max(idventa) as idventa, empresas.nombreempresa, empresas.rfcempresa FROM venta
				INNER JOIN empresas ON venta.idempresa = empresas.idempresa
				WHERE empresas.idempresa=$intEmpresa";

			$request = $this->select($sql);

			if(empty($request)){
				$sql = "SELECT nombreempresa, rfcempresa, '0' as idventa from empresas  where idempresa=$intEmpresa";
				$request = $this->select($sql);
			}

			//dep($request);
			return $request;
		}

		public function empresa(){
            $sql="select nombreempresa as nombre from empresas where status = 1 and idempresa=".intval($_SESSION['userData']['idempresa']);
            $request = $this->select($sql);
            $nempresa = $request['nombre'];
            return $nempresa;
        }



/*
		public function insertImage(int $idproducto, string $imagen){
			$this->intIdProducto = $idproducto;
			$this->strImagen = $imagen;
			$query_insert  = "INSERT INTO imagen(productoid,img) VALUES(?,?)";
	        $arrData = array($this->intIdProducto,
        					$this->strImagen);
	        $request_insert = $this->insert($query_insert,$arrData);
	        return $request_insert;
		}

		public function selectImages(int $idproducto){
			$this->intIdProducto = $idproducto;
			$sql = "SELECT productoid,img
					FROM imagen
					WHERE productoid = $this->intIdProducto";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deleteImage(int $idproducto, string $imagen){
			$this->intIdProducto = $idproducto;
			$this->strImagen = $imagen;
			$query  = "DELETE FROM imagen 
						WHERE productoid = $this->intIdProducto 
						AND img = '{$this->strImagen}'";
	        $request_delete = $this->delete($query);
	        return $request_delete;
		}
*/

	}
 ?>