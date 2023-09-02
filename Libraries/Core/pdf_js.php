<?php
require_once("Libraries/Core/code128.php");

class PDF_JavaScript extends PDF_Code128{
    protected $javascript;
    protected $n_js;

    function IncludeJS($script) {
        $this->javascript = $script;
    }

    function _putjavascript() {
        $this->_newobj();
        $this->n_js = $this->n;
        $this->_put('<<');
        $this->_put('/Names [(EmbeddedJS) '.($this->n+1).' 0 R]');
        $this->_put('>>');
        $this->_put('endobj');
        $this->_newobj();
        $this->_put('<<');
        $this->_put('/S /JavaScript');
        $this->_put('/JS '.$this->_textstring($this->javascript));
        $this->_put('>>');
        $this->_put('endobj');
    }

    function _putresources() {
        parent::_putresources();
        if (!empty($this->javascript)) {
            $this->_putjavascript();
        }
    }

    function _putcatalog() {
        parent::_putcatalog();
        if (!empty($this->javascript)) {
            $this->_put('/Names <</JavaScript '.($this->n_js).' 0 R>>');
        }
    }

    function AutoPrint($printer='')
    {
        // Open the print dialog
        if($printer)
        {
            $printer = str_replace('\\', '\\\\', $printer);
            $script = "var pp = getPrintParams();";
            $script .= "pp.interactive = pp.constants.interactionLevel.full;";
            $script .= "pp.printerName = '$printer'";
            $script .= "print(pp);";
        }
        else
            $script = 'print(true);';
        $this->IncludeJS($script);
    }

    function AutoPrintToPrinter($server, $printer, $dialog=false){
        //Imprime en impresora compartida y requiere minimo Acrobat 6
        $script = "var pp = getPrintParams();";
        if($dialog){
            $script .= "pp.interactive = pp.constants.interactionLevel.full;";
        }else{
            $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
        }
        $script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
        $script .= "print(pp);";
        $this->IncludeJS($script);
    }
}

//class PDF_Autoprint extends PDF_JavaScript{
 /*   function AutoPrint($dialof=false){
        //Abre el dialogo o  imprime directamente
        $param=($dialog ? 'true' : 'false');
        $script=print($param);
        $this->IncludeJS($script);
    }

    function AutoPrintToPrinter($server, $printer, $dialog=false){
        //Imprime en impresora compartida y requiere minimo Acrobat 6
        $script = "var pp = getPrintParams();";
        if($dialog){
            $script .= "pp.interactive = pp.constants.interactionLevel.full;";
        }else{
            $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
        }
        $script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
        $script .= "print(pp);";
        $this->IncludeJS($script);
    }*/
//}

?>