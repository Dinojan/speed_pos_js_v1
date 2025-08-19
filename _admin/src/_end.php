 </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <?php include('src/_control_sidebar.php'); ?>
    <!-- /.control-sidebar -->
    <!-- Main Footer -->
    <?php include('src/_footer.php'); ?>
</div>
</body>
<!-- Bootstrap -->
<script src="../assets/plugins/bootstrap/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="../assets/plugins/overlayScrollbars/jquery.overlayScrollbars.min.js"></script>
<!-- theme App -->
<script src="../assets/nit/js/theme.js"></script>
<!-- jQuery Mapael -->
<script src="../assets/plugins/jquery/jquery.mousewheel.js"></script>
<script src="../assets/plugins/jquery/raphael.min.js"></script>
<script src="../assets/plugins/jquery/jquery.mapael.min.js"></script>
<script src="../assets/plugins/jquery/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="../assets/plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="../assets/nit/js/demo.js"></script> -->

<script src="../assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="../assets/plugins/toastr/toastr.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatables/responsive.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="../assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
<script src="../assets/plugins/jszip/jszip.min.js"></script>
<script src="../assets/plugins/pdf/pdfmake.min.js"></script>
<script src="../assets/plugins/pdf/vfs_fonts.js"></script>
<script src="../assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="../assets/plugins/datatables/buttons.print.min.js"></script>
<script src="../assets/plugins/datatables/buttons.colVis.min.js"></script>
<script src="../assets/plugins/pdf/pdf.min.js"></script>
<script src="../assets/plugins/pdf/pdf.worker.min.js"></script>
<!-- Select2 -->
<script src="../assets/plugins/select2/select2.full.min.js"></script>
<!-- pace-progress -->
<script src="../assets/plugins/pace-progress/pace.min.js"></script>
<script src="../assets/plugins/jquery/ui-bootstrap-tpls.js"></script>
<script src="../assets/nit/js/nit.min.js"></script>
<script src="../assets/nit/js/angular/controller/<?= $document->getController(); ?>.js"></script>
<?php foreach ($scripts as $script) : ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php endforeach; ?>
</body>
</html>