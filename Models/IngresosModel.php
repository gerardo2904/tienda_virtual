<?php 

	class IngresosModel extends Mysql
	{
		private $intIdIngreso;
		private $intIdProveedor;
		private $intIdPersona; 
		private $strComprobante;
		private $strImpuesto;
		private $intStatus;
		private $strNotas;
		private $intEmpresa;

        private $intProducto;
        private $intCantidad;
        private $intPrecioc;
        //private $strEtiqueta;

		public function __construct()
		{
			parent::__construct();
		}

		public function selectIngresos(){
			$intEmpresa = intval($_SESSION['userData']['idempresa']);

			$sql = "SELECT i.idingreso, i.idproveedor, i.idpersona, i.comprobante, i.impuesto,
                concat(p.nombres, ' ', p.apellidos) as nombre_proveedor,
                i.notas, i.status,
                (select if(count(precioc)=0,0,sum(precioc)) from detalle_ingreso where idingreso = i.idingreso ) as total,
                (select if(count(precioc)=0,0,sum(precioc))*i.impuesto from detalle_ingreso where idingreso = i.idingreso ) as pimpuesto,
                (select if(count(precioc)=0,0,sum(precioc))*(1+i.impuesto) from detalle_ingreso where idingreso = i.idingreso ) as grantotal
                FROM ingreso i 
                INNER JOIN persona p
                ON i.idproveedor = p.idpersona
                WHERE p.status != 0 AND i.idempresa = ".$intEmpresa;
			$request = $this->select_all($sql);
			return $request;
		}	

        public function selectDetalleIngresos(int $idingreso){
            $sql = "SELECT i.iddetalle_ingreso, i.idingreso, i.idproducto, i.cantidad, i.precioc,
                p.nombre as nombre_producto
                (i.cantidad*i.precioc) as subtotal_producto
                FROM detalle_ingreso i 
                INNER JOIN producto p
                ON i.idproducto = p.idproducto
                WHERE i.idingreso = ".$idingreso;
			$request = $this->select_all($sql);
			return $request;
        }

        /*string $ruta, poner antes de status*/
		public function insertIngreso(int $idproveedor, int $idpersona, string $comprobante, string $impuesto, string $notas, int $status, int $empresa){
			$this->intIdProveedor = $idproveedor;
            $this->intIdPersona = $idpersona;
            $this->strComprobante = $comprobante;
            $this->strImpuesto = $impuesto;
            $this->strNotas = $notas;
            $this->intStatus = $status;
			$this->intEmpresa = $empresa;

			$return = 0;
			$sql = "SELECT * FROM ingreso WHERE comprobante = '{$this->strComprobante}'";
			$request = $this->select_all($sql);
			if(empty($request))
			{
                /*ruta,  poner antes de status*/  /*,? poner al final de values*/
				$query_insert  = "INSERT INTO ingreso(idproveedor, idpersona, comprobante, impuesto, notas, status, idempresa) 
								  VALUES(?,?,?,?,?,?,?)";
                //$this->strRuta, poner antes de intStatus
	        	$arrData = array($this->intIdProveedor,
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
		public function updateIngreso(int $idingreso, int $idproveedor, int $idpersona, string $comprobante, string $impuesto, string $notas, int $status, int $empresa){
			$this->intIdIngreso = $idingreso;
            $this->intIdProveedor = $idproveedor;
            $this->intIdPersona = $idpersona;
            $this->strComprobante = $comprobante;
            $this->strImpuesto = $impuesto;
            $this->strNotas = $notas;
            $this->intStatus = $status;
			$this->intEmpresa = $empresa;
            
			//$this->strRuta = $ruta;
			
			$return = 0;
			$sql = "SELECT * FROM ingreso WHERE comprobante = '{$this->strComprobante}' AND idingreso != $this->intIdIngreso ";
			$request = $this->select_all($sql);
			if(empty($request))
			{ 
                //ruta=?,
				$sql = "UPDATE ingreso SET idproveedor=?,idpersona=?,comprobante=?,impuesto=?,status=?,notas=?, idempresa=? 
						WHERE idingreso = $this->intIdIngreso ";
                 // $this->strRuta,
				$arrData = array($this->intIdProveedor,
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
									string $precio_compra, 
									int $stock, 
									int $empresa){

			$this->intIdProducto = $idproducto;
			$this->intCodigo = $codigo;
			$this->intPrecio_compra = $precio_compra;
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
				$arrData = array($this->intPrecio_compra, $cantidad_prod);
				//dep($sql);dep($arrData);die();

	        	$request = $this->update($sql,$arrData);
	        	$return = $request;
				//dep($return);die();
			}else{
				$return = "no existe";
			}
	        return $return;
		}


        public function updateDetalleIngreso($idIngreso, $idProducto, $cantidad, $precioc){
            $this->intIdIngreso = $idIngreso;
            $this->intProducto  = $idProducto;
            $this->intCantidad  = $cantidad;
            $this->intPrecioc   = $precioc;
            //$this->strEtiqueta  = $etiqueta;
            
            $return = 0;

            $query_insert  = "INSERT INTO detalle_ingreso(idingreso, idproducto, cantidad, precioc) 
								  VALUES(?,?,?,?)";
            
	        $arrData = array($this->intIdIngreso,
        						$this->intProducto,
        						$this->intCantidad,
        						$this->intPrecioc);
            
            $request_insert = $this->insert($query_insert,$arrData);
	        $return = $request_insert;

            return $return;
        }

		public function selectIngreso(int $idingreso){
			$this->intIdIngreso = $idingreso;
			$sql = "SELECT i.idingreso, i.idproveedor, i.idpersona, i.comprobante, i.impuesto,
                concat(p.nombres, ' ', p.apellidos) as nombre_proveedor,
                i.notas, i.status,
                (select if(count(precioc)=0,0,sum(precioc)) from detalle_ingreso where idingreso = i.idingreso ) as total,
                (select if(count(precioc)=0,0,sum(precioc))*i.impuesto from detalle_ingreso where idingreso = i.idingreso ) as pimpuesto,
                (select if(count(precioc)=0,0,sum(precioc))*(1+i.impuesto) from detalle_ingreso where idingreso = i.idingreso ) as grantotal, i.created_at
                FROM ingreso i 
                INNER JOIN persona p
                ON i.idproveedor = p.idpersona
                WHERE i.idingreso = $this->intIdIngreso ";
            
			$request = $this->select($sql);
			return $request;

		}
        
        public function selectProductos(int $idingreso){
			$this->intIdIngreso = $idingreso;
			$sql = "SELECT i.iddetalle_ingreso, i.idingreso, i.idproducto, i.cantidad, i.precioc,
                    p.codigo, p.nombre, p.marca, p.descripcion, p.categoriaid,
					(select img from imagen where productoid = i.idproducto limit 1) as img
					FROM detalle_ingreso i
                    INNER JOIN producto p
                    ON i.idproducto = p.idproducto
					WHERE idingreso = $this->intIdIngreso";
			$request = $this->select_all($sql);
			return $request;
		}
        
        public function deleteDetalleIngreso(int $iddetalleingreso){
			$sql = "DELETE FROM detalle_ingreso WHERE iddetalle_ingreso = $iddetalleingreso";
			$request_delete = $this->delete($sql);
	        return $request_delete;
        }

        public function deleteIngreso(int $idingreso){
			$this->intIdIngreso = $idingreso;
			//$sql = "UPDATE ingreso SET status = ? WHERE idingreso = $this->intIdIngreso ";
            //$arrData = array(0);
			//$request = $this->update($sql,$arrData);
            
            $sql_sel = "SELECT iddetalle_ingreso FROM detalle_ingreso WHERE idingreso = $this->intIdIngreso";
            $request_sel = $this->select_all($sql_sel);
            if(empty($request_sel)){
                $sql="DELETE FROM ingreso WHERE idingreso = $this->intIdIngreso";   
			
                $request = $this->delete($sql);    
            }else{
                $sql="DELETE ingreso,detalle_ingreso FROM ingreso
                            INNER JOIN
                            detalle_ingreso ON detalle_ingreso.idingreso = ingreso.idingreso 
                            WHERE
                            ingreso.idingreso = $this->intIdIngreso";   
			
                $request = $this->delete($sql);    
            }
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