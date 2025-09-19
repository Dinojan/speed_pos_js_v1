<nav class="main-header navbar <?= style_class('nav_class') ?>">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <?php if (current_nav() == '123'): ?>
            <li class="nav-item">
              <a class="nav-link fw-bold text-warning" href="dashboard.php" role="button">
                    <!-- Show on large screens (desktop) -->
                    <b class="d-none d-lg-block"><?php echo limit_char(store('name'), 20); ?></b>
                    <!-- Show on mobile only -->
                    <b class="d-lg-none"><?php echo limit_char(store('name'), 10); ?></b>
                </a>

            </li>
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php" role="button"><i class="fa fa-home"></i>
                </a>
            </li>
        <?php endif; ?>
        <?php //if (current_nav() != 'pos'): ?>
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>

           
        <?php // endif; ?>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" onClick="return false;" id="live_datetime"></a>
        </li>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
         <li class="nav-item d-none d-lg-block">
            <form class="form-sidebar" action="itemToInvoice.php" method="get">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Search Product Barcode" name="pc"
                        value="<?= isset($_GET['pc']) ? $_GET['pc'] : '' ?>" required>
                    <div class="input-group-append ">
                        <button type="submit" class="btn btn-default">
                            <i class="fa fa-barcode"></i>
                        </button>
                    </div>
                </div>
            </form>
        </li>
    
       
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" id="fullscreen-trigger" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="logout.php" role="button" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>
</nav>