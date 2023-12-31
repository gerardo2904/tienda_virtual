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
		private $intProveedor;

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
				c.nombre as categoria, p.idproveedor, CONCAT(pe.nombres, ' ', pe.apellidos) as proveedor, 
				p.precio, p.precio_compra, p.stock, p.status, p.idempresa, 
				(select img from imagen where productoid = p.idproducto limit 1) as img
				FROM producto p 
				INNER JOIN categoria c ON p.categoriaid = c.idcategoria
				INNER JOIN persona pe ON p.idproveedor = pe.idpersona
        		WHERE p.status != 0 AND p.idempresa= $intEmpresa";
			$request = $this->select_all($sql);
			return $request;
		}	

		public function selectProductosV(){
			$intEmpresa = intval($_SESSION['userData']['idempresa']);
            $sql = "SELECT p.idproducto, p.codigo, p.nombre, p.marca, p.descripcion, p.categoriaid,
	                    c.nombre as categoria, p.idproveedor, CONCAT(pe.nombres, ' ', pe.apellidos) as proveedor,
	                    p.precio, p.precio_compra, p.stock, p.status, p.idempresa, 
                        (select img from imagen where productoid = p.idproducto limit 1) as img
        FROM producto p 
        INNER JOIN categoria c ON p.categoriaid = c.idcategoria
		INNER JOIN persona pe ON p.idproveedor = pe.idpersona
        WHERE p.status != 0 AND p.idempresa= $intEmpresa AND p.stock > 0 ";
		$request = $this->select_all($sql);
		return $request;
		}	


        /*string $ruta, poner antes de status*/
		public function insertProducto(string $nombre, string $marca, string $descripcion, int $codigo, int $categoriaid, string $precio, string $precio_compra, int $stock,  int $status, int $empresa, int $proveedor){
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
			$this->intProveedor = $proveedor;
			$return = 0;
			$sql = "SELECT * FROM producto WHERE codigo = '{$this->intCodigo}'";
			$request = $this->select_all($sql);
			if(empty($request))
			{
                /*ruta,  poner antes de status*/  /*,? poner al final de values*/
				$query_insert  = "INSERT INTO producto(categoriaid, codigo, nombre, marca, descripcion, precio, precio_compra, stock,  status, idempresa, idproveedor) 
								  VALUES(?,?,?,?,?,?,?,?,?,?,? )";
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
								$this->intEmpresa,
								$this->intProveedor);
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
		public function updateProducto(int $idproducto, string $nombre, string $marca, string $descripcion, int $codigo, int $categoriaid, string $precio, string $precio_compra, int $stock,  int $status, int $empresa, int $proveedor){
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
			$this->intProveedor = $proveedor;

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
							idempresa=?,
							idproveedor=?  
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
								$this->intEmpresa,
								$this->intProveedor);

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
							p.idempresa,
							p.idproveedor,
							CONCAT(pe.nombres,' ', pe.apellidos) as proveedor
					FROM producto p
					INNER JOIN categoria c ON p.categoriaid = c.idcategoria
					INNER JOIN persona pe ON p.idproveedor = pe.idpersona
					WHERE idproducto = $this->intIdProducto AND p.idempresa= $intEmpresa";
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

		public function selectProdExcel(){
			$intEmpresa = intval($_SESSION['userData']['idempresa']);
			
            $sql = "SELECT codigo, CONCAT(marca,' ',nombre) as nombre, descripcion, precio 
					from producto WHERE status !=0 AND idempresa= $intEmpresa and stock>0";
			$request = $this->select_all($sql);
			return $request;
		}

		public function insertProveedor(string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, string $nit, string $nomFiscal, string $dirFiscal, string $strNumExt, string $strNumInt, $strColonia, $strCP, int $intlistEstado, int $intlistCiudad, int $intlistRegimen, int $intlistCFDI)
        {
            $this->strIdentificacion = $identificacion;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strEmail = $email;
            $this->strPassword = $password;
            $this->intTipoId = $tipoid;
            $this->strNit = $nit;
            $this->strNomFiscal = $nomFiscal;
            $this->strDirFiscal = $dirFiscal;
            $this->strNumExt = $strNumExt;
            $this->strNumInt = $strNumInt;
            $this->strColonia = $strColonia;
            $this->strCP = $strCP;
            $this->intlistEstado = $intlistEstado;
            $this->intlistCiudad = $intlistCiudad;
            $this->intlistRegimen = $intlistRegimen;
            $this->intlistCFDI = $intlistCFDI;
            $return = 0;

            $sql = "SELECT * FROM persona WHERE email_user = '{$this->strEmail}' /*or identificacion = '{$this->strIdentificacion}' */";
            $request = $this->select_all($sql);

            if(empty($request)){
                $query_insert = "INSERT INTO persona(identificacion, nombres, apellidos, telefono, email_user, password, rolid, nit, nombrefiscal, direccionfiscal, numext, numint, colonia, cp, estado, municipio, regfiscal,usocfdi ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $arrData = array($this->strIdentificacion, 
                                 $this->strNombre, 
                                 $this->strApellido, 
                                 $this->intTelefono, 
                                 $this->strEmail, 
                                 $this->strPassword, 
                                 $this->intTipoId, 
                                 $this->strNit,
                                 $this->strNomFiscal,
                                 $this->strDirFiscal,
                                 $this->strNumExt,
                                 $this->strNumInt,
                                 $this->strColonia,
                                 $this->strCP,
                                 $this->intlistEstado,
                                 $this->intlistCiudad,
                                 $this->intlistRegimen,
                                 $this->intlistCFDI);
                $request_insert = $this->insert($query_insert, $arrData);
                $return = $request_insert;

            }else{
                $return = 'exist';
            }
            return $return;
        }

		public function updateProveedor(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, string $nit, string $nomFiscal, string $dirFiscal, string $strNumExt, string $strNumInt, string $strColonia, string $strCP, int $intlistEstado, int $intlistCiudad, int $intlistRegimen, int $intlistCFDI){
            $this->intIdUsuario = $idUsuario;
            $this->strIdentificacion = $identificacion;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strEmail = $email;
            $this->strPassword = $password;
            $this->strNit = $nit;
            $this->strNomFiscal = $nomFiscal;
            $this->strDirFiscal = $dirFiscal;
            $this->strNumExt = $strNumExt;
            $this->strNumInt = $strNumInt;
            $this->strColonia = $strColonia;
            $this->strCP = $strCP;
            $this->intlistEstado = $intlistEstado;
            $this->intlistCiudad = $intlistCiudad;
            $this->intlistRegimen = $intlistRegimen;
            $this->intlistCFDI = $intlistCFDI;

            $sql = "SELECT * FROM persona 
                    WHERE (email_user = '{$this->strEmail}' AND idpersona != $this->intIdUsuario)
                       /*OR (identificacion = '{$this->strIdentificacion}' AND idpersona != $this->intIdUsuario) */ ";
            $request = $this->select_all($sql);  
            
            if(empty($request)){
                if($this->strPassword != ""){
                    $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, password=?, nit=?, nombrefiscal=?, direccionfiscal=?, numext=?, numint=?, colonia=?, cp=?, estado=?, municipio=?, regfiscal=?, usocfdi=? WHERE idpersona = $this->intIdUsuario";
                    $arrData = array($this->strIdentificacion, 
                                    $this->strNombre,
                                    $this->strApellido,
                                    $this->intTelefono,
                                    $this->strEmail,
                                    $this->strPassword,
                                    $this->strNit,
                                    $this->strNomFiscal,
                                    $this->strDirFiscal,
                                    $this->strNumExt,
                                    $this->strNumInt,
                                    $this->strColonia,
                                    $this->strCP,
                                    $this->intlistEstado,
                                    $this->intlistCiudad,
                                    $this->intlistRegimen,
                                    $this->intlistCFDI);
                }else {
                    $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, nit=?, nombrefiscal=?, direccionfiscal=?, numext=?, numint=?, colonia=?, cp=?, estado=?, municipio=?, regfiscal=?, usocfdi=?  WHERE idpersona = $this->intIdUsuario";
                    $arrData = array($this->strIdentificacion, 
                                    $this->strNombre,
                                    $this->strApellido,
                                    $this->intTelefono,
                                    $this->strEmail,
                                    $this->strNit,
                                    $this->strNomFiscal,
                                    $this->strDirFiscal,
                                    $this->strNumExt,
                                    $this->strNumInt,
                                    $this->strColonia,
                                    $this->strCP,
                                    $this->intlistEstado,
                                    $this->intlistCiudad,
                                    $this->intlistRegimen,
                                    $this->intlistCFDI);
                }
                $request = $this->update($sql,$arrData);
            }else{
                $request = "exist";
            }
            return $request;
        }
		
	}
 ?>