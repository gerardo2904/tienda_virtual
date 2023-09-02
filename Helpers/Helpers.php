<?php
    function base_url(){
        return BASE_URL;
    }

    function media(){
        return BASE_URL."Assets";
    }

    function headerAdmin($data=""){
        $view_header = "Views/Template/header_admin.php";
        require_once($view_header);
    }

    function footerAdmin($data=""){
        $view_footer = "Views/Template/footer_admin.php";
        require_once($view_footer);
    }

    function headerTienda($data=""){
        $view_header = "Views/Template/header_tienda.php";
        require_once($view_header);
    }

    function footerTienda($data=""){
        $view_footer = "Views/Template/footer_tienda.php";
        require_once($view_footer);
    }

    function dep($data){
        $format  = print_r("<pre>");
        $format .= print_r($data);
        $format .= print_r("</pre>");
        return $format;
    }

    function getModal(string $nameModal, $data){
        $view_modal = "Views/Template/Modals/{$nameModal}.php";
        require_once $view_modal;
    }

    function getFile(string $url, $data)
    {
        ob_start();
        require_once("Views/{$url}.php");
        $file = ob_get_clean();
        return $file;        
    }

    // Envio de correos
    function sendEmail($data,$template)
    {
        $asunto = $data['asunto'];
        $emailDestino = $data['email'];
        $empresa = NOMBRE_REMITENTE;
        $remitente = EMAIL_REMITENTE;
        //ENVIO DE CORREO
        $de = "MIME-Version: 1.0\r\n";
        $de .= "Content-type: text/html; charset=UTF-8\r\n";
        $de .= "From: {$empresa} <{$remitente}>\r\n";
        ob_start();
        require_once("Views/Template/Email/".$template.".php");
        $mensaje = ob_get_clean();
        $send = mail($emailDestino, $asunto, $mensaje, $de);
        return $send;
    }

    function getPermisos(int $idmodulo){
        require_once("Models/PermisosModel.php");
        $objPermisos = new PermisosModel();
        $idrol = $_SESSION['userData']['idrol'];
    
        $arrPermisos = $objPermisos->permisosModulo($idrol);
        $permisos = '';
        $permisosMod = '';
        if(count($arrPermisos) > 0){
            $permisos = $arrPermisos;
            $permisosMod = isset($arrPermisos[$idmodulo]) ? $arrPermisos[$idmodulo] : "";

        }
        $_SESSION['permisos'] = $permisos;
        $_SESSION['permisosMod'] = $permisosMod;
    }

    function sessionUser(int $idpersona){
        require_once("Models/LoginModel.php");
        $objLogin = new LoginModel();
        $request = $objLogin->sessionLogin($idpersona);
        return $request;
    }

    function sessionStart(){
        session_start();
        $inactive = 60;
        if(isset($_SESSION['timeout'])){
            $session_in = time() - $_SESSION['inicio'];
            if($session_in > $inactive){
                header("Location: ".BASE_URL."logout");
            }
        }else{
            header("Location: ".BASE_URL."logout");
        }
    }

    function uploadImage(array $data, string $name){
        $url_temp = $data['tmp_name'];
        $destino = 'Assets/images/uploads/'.$name;
        $move = move_uploaded_file($url_temp, $destino);
        return $move;
    }

    function deleteFile(string $name){
        unlink('Assets/images/uploads/'.$name);
    }

    function strClean($strCadena){
        $string  = preg_replace(['/\s+/','/^\s|\s$/'],[' ',''],$strCadena);
        $string  = trim($string); //Elimina espacios en blando al inicio y fin.
        $string  = stripslashes($string); // Elimina las \ invertidas.
        $string  = str_replace("<script>","",$string);
        $string  = str_replace("</script>","",$string);
        $string  = str_replace("<script src>","",$string);
        $string  = str_replace("<script type =>","",$string);
        $string  = str_replace("SELECT * FROM","",$string);
        $string  = str_replace("DELETE FROM","",$string);
        $string  = str_replace("INSERT INTO","",$string);
        $string  = str_replace("SELECT COUNT(*) FROM ","",$string);
        $string  = str_replace("DROP TABLE","",$string);
        $string  = str_replace("OR '1' = '1","",$string);
        $string  = str_replace('OR "1" = "1"',"",$string);
        $string  = str_replace('OR ´1´ = ´1´',"",$string);
        $string  = str_replace("is NULL; --","",$string);
        $string  = str_replace("is NULL; __","",$string);
        $string  = str_replace("LIKE '","",$string);
        $string  = str_replace('LIKE "',"",$string);
        $string  = str_replace("LIKE ´","",$string);
        $string  = str_replace("OR 'a' = 'a","",$string);
        $string  = str_replace('OR "a" = "a',"",$string);
        $string  = str_replace('OR ´a´ = ´a',"",$string);
        $string  = str_replace("OR ´a´ = ´a","",$string);
        $string  = str_replace("--","",$string);
        $string  = str_replace("^","",$string);
        $string  = str_replace("[","",$string);
        $string  = str_replace("]","",$string);
        $string  = str_replace("==","",$string);
        return $string;
    }

    // Genera una contraseña aleatoria de 10 caracteres.
    function passGenerator($Length = 10){
        $pass = "";
        $longitudPass = $Length;
        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $longitudCadena = strlen($cadena);
        for($i=1; $i<=$longitudPass;$i++){
            $pos = rand(0,$longitudCadena-1);
            $pass .= substr($cadena,$pos,1);
        }
        return $pass;
    }

    // Genera un Token
    function token(){
        $r1 = bin2hex(random_bytes(10));
        $r2 = bin2hex(random_bytes(10));
        $r3 = bin2hex(random_bytes(10));
        $r4 = bin2hex(random_bytes(10));        
        $token = $r1.'-'.$r2.'-'.$r3.'-'.$r4;
        return $token;
    }

    // Formato para valores monetarios.
    function formatMoney($cantidad){
        $cantidad = number_format($cantidad,2,SPD,SPM);
        return $cantidad;
    }

    function Meses(){
        $meses = array("Enero",
                       "Febrero",
                       "Marzo",
                       "Abril",
                       "Mayo",
                       "Junio",
                       "Julio",
                       "Agosto",
                       "Septiembre",
                       "Octubre",
                       "Noviembre",
                       "Diciembre");
        return $meses;
    }

?>
