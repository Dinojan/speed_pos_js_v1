<?php
$body_class = $document->getBodyClass();
$title = $document->getTitle();
$description = $document->getDescription();
$keywords = $document->getKeywords();
$styles = $document->getStyles();
$scripts = $document->getScripts();
$query_string = '';
if (!empty($request->get)) {
  $inc = 1;
  foreach ($request->get as $key => $value) {
    if (!in_array($key, array('from', 'to', 'filter', 'ftype', 'type'))) {
      if ($inc == 1) {
        $query_string .= '?' . $key . '=' . $value;
      } else {
        $query_string .= '&' . $key . '=' . $value;
      }
    }
    $inc++;
  }
} else {
  $query_string = '?filter=yes';
}
$query_string = str_replace(array('&'), '?', $query_string);
?>
<!DOCTYPE html>
<html lang="<?php echo $document->langTag($active_lang); ?>" ng-app="NITAgApp">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $title ? $title . ' &raquo; ' : null; ?><?php echo store('name'); ?></title>
  <!-- Meta Description -->
  <?php if ($description) : ?>
    <meta name="description" content="<?php echo $description; ?>">
  <?php endif; ?>

  <!-- Meta Keywords -->
  <?php if ($keywords) : ?>
    <meta name="keywords" content="<?php echo $keywords; ?>">
  <?php endif; ?>
  <link rel="shortcut icon" type="image/png" href="../assets/nit/img/icon.png" />
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../assets/plugins/overlayScrollbars/OverlayScrollbars.min.css">
  <!-- pace-progress -->
  <link rel="stylesheet" href="../assets/plugins/pace-progress/themes/black/pace-theme-flat-top.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/nit/css/theme.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../assets/plugins/datatables/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables/buttons.bootstrap4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="../assets/plugins/select2/select2.min.css">
  <link rel="stylesheet" href="../assets/plugins/select2/select2-bootstrap4.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="../assets/plugins/sweetalert2/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../assets/plugins/toastr/toastr.min.css">
  <!-- Datepicker3 CSS-->
  <link type="text/css" href="../assets/plugins/datepicker/datepicker3.css" type="text/css" rel="stylesheet">
  <!-- jquery UI CSS -->
  <link type="text/css" href="../assets/plugins/jquery/jquery-ui.min.css" type="text/css" rel="stylesheet">
  <!-- Perfect-scrollbar CSS -->
  <link type="text/css" href="../assets/plugins/perfectScroll/perfect-scrollbar.css" type="text/css" rel="stylesheet">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="../assets/plugins/dropzone/dropzone.min.css">
  <!-- jQuery -->
  <script src="../assets/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery Ui JS -->
  <script src="../assets/plugins/jquery/jquery-ui.min.js" type="text/javascript"></script>
  <!-- Perfect Scrollbar JS -->
  <script src="../assets/plugins/perfectScroll/perfect-scrollbar.jquery.min.js" type="text/javascript"></script>
  <!-- Accounting JS -->
  <script src="../assets/plugins/accounting/accounting.min.js" type="text/javascript"></script>
  <!-- dropzonejs -->
  <script src="../assets/plugins/dropzone/dropzone.min.js"></script>
  <script src="../assets/nit/js/main.js"></script>
  <script src="../assets/nit/js/angular/angular.min.js"></script>
  <script src="../assets/nit/js/angular/angularApp.js"></script>
  <script src="../assets/nit/js/angular/modal.js"></script>
  <!-- Bootstrap Datepicker JS -->
  <script src="../assets/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
  <script src="../assets/plugins/lodash.js/lodash.min.js"></script>

  <script>
    var baseUrl = "<?php echo trim(root_url(), '/'); ?>";
    var adminDir = "<?php echo ADMINDIRNAME; ?>";
    var store_name = "SPEED POS";
    var store = <?php echo json_encode(store()); ?>;
    var timezone = "<?php echo addslashes($timezone); ?>";
    var copyright = "NORTHERN IT HUB";
    var logo = '../assets/nit/img/logo.png';
    var user = '';
    var current_nav = "<?php echo current_nav() ?>";
  </script>

</head>

<body class="<?= style_class('body_class') . " " . $body_class ?>   <?php echo (current_nav() != 'pos') ? 'sidebar-mini' : '' ?> layout-fixed skin-green pace-primary" <?php echo (current_nav() == 'pos') ? 'style="min-height: 100vh;"' : '' ?>>
  <!--begin::App Wrapper-->
  <div class="app-wrapper1" ng-controller="<?= $document->getController(); ?>">
    <!-- Preloader -->
    <?php include('src/_preloader.php'); ?>
    <!-- Navbar -->
    <?php if (current_nav() != 'pos') {
      include('src/_nav.php');
    } ?>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <?php if (current_nav() != 'pos') {
      include('src/_aside.php');
    } ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <?php if (current_nav() != 'pos') { ?>
        <div class="content-header">
          <div class="container-fluid ">
            <div class="row mb-2">
              <div class="col-sm-6">
                <strong class="m-0 text-lg"><?= $document->getTitle(); ?> </strong>
                <?php include("../_inc/template/partials/apply_filter.php"); ?>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php"><?= trans('title_home') ?></a></li>
                  <li class="breadcrumb-item active"><?= $document->getTitle(); ?> </li>
                </ol>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
      <?php } ?>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="<?php echo (current_nav() != 'pos') ? 'content ' : '' ?>">
        <div class="<?php echo (current_nav() != 'pos') ? 'container-fluid ' : '' ?>">