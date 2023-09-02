<?php
    //define("BASE_URL","http://localhost:9090/tienda_virtual/")
    //const BASE_URL = "https://www.mantoplustj.com/tienda_virtual/";
    const BASE_URL = "http://localhost:9090/tienda_virtual/";

    //Zona horaria 
    date_default_timezone_set('America/Tijuana');

    // Datos de conexión a la base de datos.

    /*
    const DB_HOST     = "mantoplustj.com:3306";
    const DB_NAME     = "u131477238_tv";
    const DB_USER     = "u131477238_tv";
    const DB_PASSWORD = "7^ex=5>ayjNh";
    const DB_CHARSET  = "utf8";

    */

    const DB_HOST     = "db:3306";
    const DB_NAME     = "db_tiendavirtual";
    const DB_USER     = "root";
    const DB_PASSWORD = "atomicstatus";
    const DB_CHARSET  = "utf8";
    
    const SPD = "."; // Seperador de decimales.
    const SPM = ","; // Separador de miles.

    const SMONEY = "MXN"; //Simbolo de moneda.

    const NOMBRE_REMITENTE = "Mantoplus";
	const EMAIL_REMITENTE = "no-reply@mantoplus.com";
	const NOMBRE_EMPRESA = "Oftalmo Tijuana";
	const WEB_EMPRESA = "http://www.mantoplus.com";
   
    const RCLIENTES = 7;
    const RPROVEEDORES = 10;

?>