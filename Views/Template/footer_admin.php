<script> 
  const base_url = "<?= base_url(); ?>";
  const smony = "<?= SMONEY; ?>";
</script>

<!-- Essential javascripts for application to work-->
    <?php /*<script src="<?= media(); ?>/js/jquery-3.3.1.min.js"></script> */?>  
    <script src="<?= media(); ?>/js/plugins/jquery-3.7.0/jquery-3.7.0.min.js"></script>
    
    <script src="<?= media(); ?>/js/popper.min.js"></script>
    <script src="<?= media(); ?>/js/bootstrap.min.js"></script>
    <script src="<?= media(); ?>/js/main.js"></script>
    <script src="<?= media(); ?>/js/fontawesome.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?= media(); ?>/js/plugins/pace.min.js"></script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="<?= media(); ?>/js/plugins/sweetalert.min.js"></script>
    <script type="text/javascript" src="<?= media(); ?>/js/tinymce/tinymce.min.js"></script>

    <!-- Data table plugin-->
    <?php /*<script type="text/javascript" src="<?= media(); ?>/js/plugins/jquery.dataTables.min.js"></script> */ ?>
    <script type="text/javascript" src="<?= media(); ?>/js/plugins/DataTables_1.13.7/jquery.dataTables.min.js"></script> 
    
    <script type="text/javascript" src="<?= media(); ?>/js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= media(); ?>/js/plugins/bootstrap-select.min.js"></script>
    <!-- Botones para exportar -->
    <?php /*<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script> */ ?>
    <script type="text/javascript" src="<?= media(); ?>/js/plugins/buttons_dataTables_2.4.2/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="<?= media(); ?>/js/plugins/SearchBuilder_1.6.0/dataTables.searchBuilder.min.js"></script>
    <script type="text/javascript" src="<?= media(); ?>/js/plugins/DataTablesdateTime_1.5.1/dataTables.dateTime.min.js"></script>
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

    
    <script src="<?= media(); ?>/js/highcharts//highcharts.js"></script>
    <script src="<?= media(); ?>/js/highcharts/exporting.js"></script>
    <script src="<?= media(); ?>/js/highcharts/export-data.js"></script>
    <script src="<?= media(); ?>/js/datepicker/jquery-ui.min.js"></script>

    
    

    
    <script type="text/javascript" src="<?= media(); ?>/js/functions_admin.js"></script>
    <script src="<?= media(); ?>/js/<?= $data['page_functions_js']; ?>"></script>


  </body>
</html>