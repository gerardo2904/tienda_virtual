<?php 

	class ProductosModel extends Mysql
	{
		private $intIdProducto;
		private $strNombre;
		private $strMarca;
		private $strDescripcion;
		private $intCodigo;
		private $intCategoriaId;
		private $intPrecio;
		private $intPrecio_compra;
		private $intStock;
		private $intStatus;
		private $strRuta;
		private $strImagen;
		private $intEmpresa;

		public function __construct()
		{
			parent::__construct();
		}

		public function selectProductos(){
			$intEmpresa = intval($_SESSION['userData']['idempresa']);
			/*$sql = "SELECT p.idproducto,
							p.codigo,
							p.nombre,
							p.descripcion,
							p.categoriaid,
							c.nombre as categoria,
							p.precio,
							p.stock,
							p.status 
					FROM producto p 
					INNER JOIN categoria c
					ON p.categoriaid = c.idcategoria
					WHERE p.status != 0 ";
                */
                $sql = "SELECT p.idproducto, p.codigo, p.nombre, p.marca, p.descripcion, p.categoriaid,
	                    c.nombre as categoria,
	                    p.precio, p.precio_compra, p.stock, p.status, p.idempresa, 
                        (select img from imagen where productoid = p.idproducto limit 1) as img
        FROM producto p 
        INNER JOIN categoria c 
        ON p.categoriaid = c.idcategoria
        WHERE p.status != 0 AND p.idempresa= $intEmpresa";
		$request = $this->select_all($sql);
		return $request;
		}	

		public function selectProductosV(){
			$intEmpresa = intval($_SESSION['userData']['idempresa']);
            $sql = "SELECT p.idproducto, p.codigo, p.nombre, p.marca, p.descripcion, p.categoriaid,
	                    c.nombre as categoria,
	                    p.precio, p.precio_compra, p.stock, p.status, p.idempresa, 
                        (select img from imagen where productoid = p.idproducto limit 1) as img
        FROM producto p 
        INNER JOIN categoria c 
        ON p.categoriaid = c.idcategoria
        WHERE p.status != 0 AND p.idempresa= $intEmpresa AND p.stock > 0 ";
		$request = $this->select_all($sql);
		return $request;
		}	


        /*string $ruta, poner antes de status*/
		public function insertProducto(string $nombre, string $marca, string $descripcion, int $codigo, int $categoriaid, string $precio, string $precio_compra, int $stock,  int $status, int $empresa){
			$this->strNombre = $nombre;
			$this->strMarca = $marca;
			$this->strDescripcion = $descripcion;
			$this->intCodigo = $codigo;
			$this->intCategoriaId = $categoriaid;
			$this->strPrecio = $precio;
			$this->intPrecio_compra = $precio_compra;
			$this->intStock = $stock;
			//$this->strRuta = $ruta;
			$this->intStatus = $status;
			$this->intEmpresa = $empresa;
			$return = 0;
			$sql = "SELECT * FROM producto WHERE codigo = '{$this->intCodigo}'";
			$request = $this->select_all($sql);
			if(empty($request))
			{
                /*ruta,  poner antes de status*/  /*,? poner al final de values*/
				$query_insert  = "INSERT INTO producto(categoriaid, codigo, nombre, marca, descripcion, precio, precio_compra, stock,  status, idempresa) 
								  VALUES(?,?,?,?,?,?,?,?,?,? )";
                //$this->strRuta, poner antes de intStatus
	        	$arrData = array($this->intCategoriaId,
        						$this->intCodigo,
        						$this->strNombre,
								$this->strMarca,
        						$this->strDescripcion,
        						$this->strPrecio,
								$this->intPrecio_compra,
        						$this->intStock,
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
		public function updateProducto(int $idproducto, string $nombre, string $marca, string $descripcion, int $codigo, int $categoriaid, string $precio, string $precio_compra, int $stock,  int $status, int $empresa){
			$this->intIdProducto = $idproducto;
			$this->strNombre = $nombre;
			$this->strMarca = $marca;
			$this->strDescripcion = $descripcion;
			$this->intCodigo = $codigo;
			$this->intCategoriaId = $categoriaid;
			$this->strPrecio = $precio;
			$this->intPrecio_compra = $precio_compra;
			$this->intStock = $stock;
			//$this->strRuta = $ruta;
			$this->intStatus = $status;
			$this->intEmpresa = $empresa;
			$return = 0;
			$sql = "SELECT * FROM producto WHERE codigo = '{$this->intCodigo}' AND idproducto != $this->intIdProducto ";
			$request = $this->select_all($sql);
			if(empty($request))
			{
                //ruta=?,
				$sql = "UPDATE producto 
						SET categoriaid=?,
							codigo=?,
							nombre=?,
							marca=?,
							descripcion=?,
							precio=?,
							precio_compra=?,
							stock=?,
							status=?,
							idempresa=? 
						WHERE idproducto = $this->intIdProducto ";
                 // $this->strRuta,
				$arrData = array($this->intCategoriaId,
        						$this->intCodigo,
        						$this->strNombre,
								$this->strMarca,
        						$this->strDescripcion,
        						$this->strPrecio,
								$this->intPrecio_compra,
        						$this->intStock,
        						$this->intStatus,
								$this->intEmpresa);

	        	$request = $this->update($sql,$arrData);
	        	$return = $request;
			}else{
				$return = "exist";
			}
	        return $return;
		}

		public function selectProducto(int $idproducto){
			$intEmpresa = intval($_SESSION['userData']['idempresa']);
			$this->intIdProducto = $idproducto;
			$sql = "SELECT p.idproducto,
							p.codigo,
							p.nombre,
							p.marca,
							p.descripcion,
							p.precio,
							p.precio_compra,
							p.stock,
							p.categoriaid,
							c.nombre as categoria,
							p.status,
							p.idempresa
					FROM producto p
					INNER JOIN categoria c
					ON p.categoriaid = c.idcategoria
					WHERE idproducto = $this->intIdProducto AND idempresa= $intEmpresa";
			$request = $this->select($sql);
			//dep($request);
			return $request;

		}

		public function selectProductoV(int $idproducto){
			$intEmpresa = intval($_SESSION['userData']['idempresa']);
			$this->intIdProducto = $idproducto;
			$sql = "SELECT precio, precio_compra, stock, codigo
					FROM producto 
					WHERE idproducto = $this->intIdProducto AND idempresa = $intEmpresa AND stock > 0";
			$request = $this->select($sql);
			return $request;

		}

		public function getCodigoMax(){
			$sql = "SELECT max(cast(codigo as decimal)) as codigo FROM producto";
			$request = $this->select($sql);
			//dep($request);
			return $request;
		}

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

		public function deleteProducto(int $idproducto){
			$this->intIdProducto = $idproducto;
			$sql = "UPDATE producto SET status = ? WHERE idproducto = $this->intIdProducto ";
			$arrData = array(0);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function empresa(){
            $sql="select nombreempresa as nombre from empresas where status = 1 and idempresa=".intval($_SESSION['userData']['idempresa']);
            $request = $this->select($sql);
            $nempresa = $request['nombre'];
            return $nempresa;
        }
		
	}
 ?>