<?php
// == login v2
include("_init.php");
if ($user->isLogged()) {
    redirect(ADMINDIRNAME . '/dashboard.php');
}
$document->setTitle('Login');
// if ($request->server['REQUEST_METHOD'] == 'POST' && $request->get['action_type'] == "LOGIN") {
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($request->get['action_type']) && $request->get['action_type'] == "LOGIN") {
    try {
        if (!isset($request->post['username']) || !isset($request->post['username'])) {
            throw new Exception(trans('error_username_or_password'));
        }
        if (empty($request->post['username'])) {
            throw new Exception(trans('error_username'));
        }
        // Validate Password
        if (empty($request->post['password'])) {
            throw new Exception(trans('error_password'));
        }

        $username = $request->post['username'];
        $password = $request->post['password'];
        // Attempt to Log In
        if ($user->login($username, $password)) {

            $statement = db()->prepare("INSERT INTO `login_logs` SET `user_id` = ?, `username` = ?, `ip` = ?");
            $statement->execute(array(user_id(), $username, get_real_ip()));
            $statement = db()->prepare("UPDATE `users` SET `last_login` = ? WHERE `id` = ?");
            $statement->execute(array(date_time(), user_id()));

            $statement = db()->prepare("DELETE FROM `login_logs` WHERE `ip` = ? AND `status` = ?");
            $statement->execute(array(get_real_ip(), 'error'));

            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode(array('msg' => trans('text_login_success'), 'sessionUserId' => $session->data['id']));
            exit();
        }
        throw new Exception(trans('error_invalide_username_password'));
    } catch (Exception $e) {

        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en" ng-app="NITAgApp">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $document->getTitle() ?> | SPEED POS (Jewelry)</title>
    <link rel="shortcut icon" type="image/png" href="assets/nit/img/icon.png" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="assets/plugins/sweetalert2/bootstrap-4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="assets/plugins/toastr/toastr.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/nit/css/theme.min.css">
    <link rel="stylesheet" href="assets/nit/css/login.css">
      <!-- jQuery -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/nit/js/angular/angular.min.js"></script>
    <script src="assets/nit/js/ui-bootstrap-tpls.min.js"></script>
    <script src="assets/nit/js/angular/angularApp.js"></script>

    <script type="text/javascript">
        var baseUrl = "<?php echo root_url(); ?>";
        var adminDir = "<?php echo ADMINDIRNAME; ?>";
        var refUrl = "<?php echo isset($request->get['redirect_to']) ? $request->get['redirect_to'] : '' ?>";
    </script>
</head>

<body class="hold-transition login-page" ng-controller="LoginController">
    <div class="row">
        <div class="col-lg-8">
            <div class="text-center">
                <div class="d-flex align-items-center justify-content-center">
                    <img src="assets/nit/img/icon.png" class="img-fluid" width="150px" alt="Logo">
                    <h2 class="text-dark ms-2">SPEED <b style="color:#E95E20;">POS</b> <small>By <b>NORTHERN IT HUB
                            </b></small> </h2>
                </div>
                <div class="text-center fw-bold h3" style="color:#E95E20;">For Jewelry Shop </div>

                <span class="h5">Improve Your Business Efficiency and Revenue <br> Using <b
                        style="color:#19519C;">SPEED</b><b style="color:#E95E20;"> POS</b> ERP Software.</span>
            </div>
        </div>


        <div class="col-lg-4 mx-auto">
            <div class="login-box mx-auto">
                <!-- /.login-logo -->
                <div class="card card-outline card-primary">
                    <div class="card-header text-center login-logo">
                        <div class="text">
                            <img src="assets/nit/img/nit.png" alt="" class="mt-2 mb-3" width="180px">
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="login-box-msg text-gray"><?php echo trans('text_login'); ?></p>

                        <form id="login-form" onsubmit="return false" method="post">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user text-primary"></span>
                                    </div>
                                </div>
                                <input type="text" class="form-control text-primary" placeholder="Email / Phone No."
                                    name="username">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock text-primary"></span>
                                    </div>
                                </div>
                                <input type="password" class="form-control text-primary" placeholder="Password"
                                    name="password">
                            </div>
                            <div class="row">
                                <!-- /.col -->
                                <div class="col-8 mx-auto">
                                    <button type="button" id="login-btn" ng-click="login()" class="btn btn-primary btn-block "
                                        data-loading-text="Logging..."><?php echo trans('button_sign_in'); ?> <i
                                            class="fas fa-sign-in-alt"></i> </button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>

    </div>

  
    <!-- Bootstrap 4 -->
    <script src="assets/plugins/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="assets/plugins/toastr/toastr.min.js"></script>
    <!-- AdminLTE App -->
    <script src="assets/nit/js/theme.min.js"></script>
    <script src="assets/nit/js/nit.min.js"></script>
    <script src="assets/nit/js/angular/controller/LoginController.js"></script>
</body>

</html>