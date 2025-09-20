<aside class="main-sidebar  elevation-4 <?= style_class('asideClass') ?>">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link <?= style_class('brandLogo') ?>">
        <img src="../assets/nit/img/icon.png" alt="logo" class="brand-image elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light font-weight-bold">SPEED POS <Small>v.1.0.0</Small></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-1 pt-2 d-flex">
            <div class="image">
                <img src="../assets/nit/img/user-avatar.png" class="img-circle elevation-2">
            </div>
            <div class="info ">
                <a href="javascript:void(0)" class="d-block text-light ">
                    <?php echo ucfirst(limit_char($user->getUserName(), 15)); ?>
                </a>
                <small class="text-light"><?= $user->getRole() ?></small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column <?= style_class('side_nav_class') ?>"
                data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item ">
                    <div class="form-inline mt-2">
                        <form class="form-sidebar" action="itemToInvoice.php" method="get">
                            <div class="input-group">
                                <input class="form-control form-control-sidebar" type="search" name="pc"
                                    placeholder="Search Item Barcode" aria-label="Search" required>
                                <div class="input-group-append">
                                    <button class="btn btn-sidebar" type="submit">
                                        <i class="fas fa-search fa-fw"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </li>
                <li class="nav-item ">
                    <a href="dashboard.php"
                        class="nav-link <?php echo current_nav() == 'dashboard' || current_nav() == 'index' ? ' active' : null; ?>">
                        <i class="nav-icon fas fa-home text-sm"></i>
                        <p><?php echo trans('menu_dashboard') ?></p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="pos.php" class="nav-link <?php echo current_nav() == 'pos' ? ' active' : null; ?>">
                        <i class="nav-icon fas fa-cash-register text-sm"></i>
                        <p><?php echo trans('menu_pos') ?></p>
                    </a>
                </li>

                <!-- Inventory menu -->
                <?php if (user_group_id() == 1 || has_permission('access', 'read_category') || has_permission('access', 'read_supplier') || has_permission('access', 'read_product')): ?>
                    <li
                        class="nav-item  <?php echo current_nav() == 'category' || current_nav() == 'product' || current_nav() == 'supplier' || current_nav() == 'supplier' ? ' menu-open' : null; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-boxes text-sm"></i>
                            <p><?= trans('menu_inventory') ?>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if (user_group_id() == 1 || has_permission('access', 'read_category')): ?>
                                <li class="nav-item">
                                    <a href="category.php"
                                        class="nav-link  <?php echo current_nav() == 'category' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="fas fa-shapes nav-icon text-sm"></i>
                                        <p><?= trans('menu_category') ?></p>

                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (user_group_id() == 1 || has_permission('access', 'read_supplier')): ?>
                                <li class="nav-item ">
                                    <a href="supplier.php"
                                        class="nav-link <?php echo current_nav() == 'supplier' || current_nav() == 'supplier_profile' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-truck-loading text-sm"></i>
                                        <p><?= trans('menu_supplier') ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_product')): ?>
                                <li class="nav-item">
                                    <a href="product.php"
                                        class="nav-link  <?php echo current_nav() == 'product' || current_nav() == 'product_details' || current_nav() == 'product_edit' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="fab fa-product-hunt nav-icon text-sm"></i>
                                        <p><?= trans('menu_product') ?></p>

                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Stock checking -->
                <?php if (user_group_id() == 1 || has_permission('access', 'read_stock_checking') || has_permission('access', 'read_role') || has_permission('access', 'read_department') || has_permission('access', 'change_password')): ?>
                    <li
                        class="nav-item  <?php echo current_nav() == 'check_stock' || current_nav() == 'in_stock_summary' || current_nav() == 'missing_stock' ? ' menu-open' : null; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-search text-sm"></i>
                            <p><?= trans('menu_stock_checking') ?>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_check_stock')): ?>
                                <li class="nav-item">
                                    <a href="check_stock.php"
                                        class="nav-link  <?php echo current_nav() == 'check_stock' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-clipboard-check text-sm"></i>
                                        <p><?= trans('menu_check_stock') ?></p>

                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_in_stock_summary')): ?>
                                <li class="nav-item">
                                    <a href="in_stock_summary.php"
                                        class="nav-link  <?php echo current_nav() == 'in_stock_summary' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-boxes text-sm"></i>
                                        <p><?= trans('menu_in_stock_summary') ?></p>

                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_missing_stock')): ?>
                                <li class="nav-item">
                                    <a href="missing_stock.php"
                                        class="nav-link  <?php echo current_nav() == 'missing_stock' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-exclamation-triangle text-sm"></i>
                                        <p><?= trans('menu_missing_stock') ?></p>

                                    </a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </li>
                <?php endif; ?>



                <!-- customer -->
                <?php if (user_group_id() == 0 || has_permission('access', 'read_customer')): ?>
                    <li class="nav-item ">
                        <a href="customer.php"
                            class="nav-link <?php echo current_nav() == 'customer' || current_nav() == 'customer_profile' ? ' active' : null; ?>">
                            <i class="nav-icon fas fa-walking text-sm"></i>
                            <p><?= trans('menu_customer') ?></p>
                        </a>
                    </li>
                <?php endif; ?>
                <!-- user -->
                <?php if (user_group_id() == 1 || has_permission('access', 'read_user') || has_permission('access', 'read_role') || has_permission('access', 'read_department') || has_permission('access', 'change_password')): ?>
                    <li
                        class="nav-item  <?php echo current_nav() == 'user' || current_nav() == 'user_group' || current_nav() == 'password' ? ' menu-open' : null; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-friends text-sm"></i>
                            <p><?= trans('menu_users') ?>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_user_group')): ?>
                                <li class="nav-item">
                                    <a href="user_group.php"
                                        class="nav-link  <?php echo current_nav() == 'user_group' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="fas fa-users nav-icon text-sm"></i>
                                        <p><?= trans('menu_user_group') ?></p>

                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (user_group_id() == 1 || has_permission('access', 'read_user')): ?>
                                <li class="nav-item">
                                    <a href="user.php"
                                        class="nav-link  <?php echo current_nav() == 'user' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="fas fa-user nav-icon text-sm"></i>
                                        <p><?= trans('menu_user') ?></p>

                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (user_group_id() == 1 || has_permission('access', 'change_password')): ?>
                                <li class="nav-item">
                                    <a href="password.php"
                                        class="nav-link  <?php echo current_nav() == 'password' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="fas fa-key nav-icon text-sm"></i>
                                        <p><?= trans('menu_change_password') ?></p>

                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- system -->
                <?php if (user_group_id() == 0 || has_permission('access', 'read_stores') || has_permission('access', 'read_currency') || has_permission('access', 'read_language') || has_permission('access', 'read_pmethod')): ?>
                    <li
                        class="nav-item  <?php echo current_nav() == 'store' || current_nav() == 'receipt_template' || current_nav() == 'store_setting' || current_nav() == 'currency' || current_nav() == 'language' || current_nav() == 'pmethod' ? ' menu-open' : null; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-toolbox text-sm"></i>
                            <p><?= trans('menu_system') ?>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li
                                class="nav-item  border-left  <?php echo current_nav() == 'store' || current_nav() == 'store_setting' ? ' menu-open' : null; ?>">
                                <a href="#" class="nav-link">
                                    <span class="line "><i class="fas fa-minus"></i></span>
                                    <i class="nav-icon fas fa-store-alt text-sm"></i>
                                    <p><?= trans('menu_stores') ?>
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview ">
                                    <?php if (user_group_id() == 1 || has_permission('access', 'read_stores')): ?>
                                        <li class="nav-item ">
                                            <a href="store.php"
                                                class="nav-link <?php echo current_nav() == 'store' ? ' active' : null; ?>">
                                                <span class="line"><i class="fas fa-minus"></i></span>
                                                <i class="fas fa-store nav-icon text-xs"></i>
                                                <p><?= trans('menu_stores') ?></p>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (user_group_id() == 1 || has_permission('access', 'update_store')): ?>
                                        <li class="nav-item">
                                            <a href="store_setting.php"
                                                class="nav-link  <?php echo current_nav() == 'store_setting' ? ' active' : null; ?>">
                                                <span class="line"><i class="fas fa-minus"></i></span>
                                                <i class="fas fa-tools nav-icon text-xs"></i>
                                                <p><?= trans('menu_store_settings') ?></p>

                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                                <?php if (user_group_id() == 1 || has_permission('access', 'receipt_template')): ?>
                            <li class="nav-item">
                                <a href="receipt_template.php?template_id=<?php echo get_preference('receipt_template') ? get_preference('receipt_template') : 1; ?>"
                                    class="nav-link  <?php echo current_nav() == 'receipt_template' ? 'active' : null; ?>">
                                    <span class="line"><i class="fas fa-minus"></i></span>
                                    <i class="fas fa-file-invoice-dollar nav-icon text-sm"></i>
                                    <p>
                                        <?php echo trans('menu_receipt_template'); ?>
                                    </p>

                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (user_group_id() == 1 || has_permission('access', 'read_currency')): ?>
                            <li class="nav-item">
                                <a href="currency.php"
                                    class="nav-link  <?php echo current_nav() == 'currency' ? ' active' : null; ?>">
                                    <span class="line"><i class="fas fa-minus"></i></span>
                                    <i class="fas fa-money-bill nav-icon text-sm"></i>
                                    <p><?= trans('menu_currency') ?></p>

                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (user_group_id() == 1 || has_permission('access', 'read_pmethod')): ?>
                            <li class="nav-item">
                                <a href="pmethod.php"
                                    class="nav-link  <?php echo current_nav() == 'pmethod' ? ' active' : null; ?>">
                                    <span class="line"><i class="fas fa-minus"></i></span>
                                    <i class="fas fa-money-bill-alt nav-icon text-sm"></i>
                                    <p>
                                        <?php echo trans('menu_pmethod'); ?>
                                    </p>

                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (user_group_id() == 1 || has_permission('access', 'read_language')): ?>
                            <li class="nav-item">
                                <a href="language.php?lang=en"
                                    class="nav-link  <?php echo current_nav() == 'language' ? ' active' : null; ?>">
                                    <span class="line"><i class="fas fa-minus"></i></span>
                                    <i class="fas fa-language nav-icon text-sm"></i>
                                    <p>
                                        <?php echo trans('menu_language'); ?>
                                    </p>

                                </a>
                            </li>
                        <?php endif; ?>

                        </ul>
                    </li>
                <?php endif; ?>
                <li class="nav-item ">
                    <a href="customer.php"
                        class="nav-link <?php echo current_nav() == 'customer' || current_nav() == 'index' ? ' active' : null; ?>">
                        <i class="nav-icon fas fa-users text-sm"></i>
                        <p><?php echo trans('menu_customer') ?></p>
                    </a>
                </li>
                <!-- // order  -->
                <?php if (user_group_id() == 1 || has_permission('access', 'read_order') || has_permission('access', 'read_role') || has_permission('access', 'read_department') || has_permission('access', 'change_password')): ?>
                    <li
                        class="nav-item  <?php echo current_nav() == 'order' || current_nav() == 'order_group' || current_nav() == 'password' ? ' menu-open' : null; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-shopping-cart text-sm"></i>
                            <p><?= trans('menu_orders') ?>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_order')): ?>
                                <li class="nav-item">
                                    <a href="order.php"
                                        class="nav-link  <?php echo current_nav() == 'order' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-plus text-sm"></i>
                                        <p><?= trans('menu_order') ?></p>

                                    </a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </li>
                <?php endif; ?>
                <!-- // sales  -->
                <?php if (user_group_id() == 1 || has_permission('access', 'read_sales') || has_permission('access', 'read_role') || has_permission('access', 'read_department') || has_permission('access', 'change_password')): ?>
                    <li
                        class="nav-item  <?php echo current_nav() == 'sales_list' || current_nav() == 'sales_summary' ? ' menu-open' : null; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-line text-sm"></i>
                            <p><?= trans('menu_sales') ?>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_pos')): ?>
                                <li class="nav-item">
                                    <a href="pos.php"
                                        class="nav-link  <?php echo current_nav() == 'pos' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-cash-register text-sm"></i>
                                        <p><?= trans('menu_pos') ?></p>

                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_sales_list')): ?>
                                <li class="nav-item">
                                    <a href="sales_list.php"
                                        class="nav-link  <?php echo current_nav() == 'sales_list' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-list text-sm"></i>
                                        <p><?= trans('menu_sales_list') ?></p>

                                    </a>
                                </li>
                            <?php endif; ?>

                            <!-- <?php // if (user_group_id() == 1 || has_permission('access', 'read_sales_return')): 
                                    ?>
                                <li class="nav-item">
                                    <a href="sales_return.php"
                                        class="nav-link  <?php // echo current_nav() == 'sales_return' ? ' active' : null; 
                                                            ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-undo text-sm"></i>
                                        <p><?php // echo trans('menu_sales_return') 
                                            ?></p>

                                    </a>
                                </li>
                            <?php // endif; 
                            ?> -->

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_sales_summary')): ?>
                                <li class="nav-item">
                                    <a href="sales_summary.php"
                                        class="nav-link  <?php echo current_nav() == 'sales_summary' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-chart-pie text-sm"></i>
                                        <p><?= trans('menu_sales_summary') ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- // report  -->
                <?php if (user_group_id() == 1 || has_permission('access', 'read_report') || has_permission('access', 'read_role') || has_permission('access', 'read_department') || has_permission('access', 'change_password')): ?>
                    <li
                        class="nav-item  <?php echo current_nav() == 'orders_reports' || current_nav() == 'due_reports' || current_nav() == 'stock_reports' || current_nav() == 'missing_reports' || current_nav() == 'cash_book' ? ' menu-open' : null; ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file-alt text-sm"></i>
                            <p><?= trans('menu_report') ?>
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_orders_reports')): ?>
                                <li class="nav-item">
                                    <a href="orders_reports.php"
                                        class="nav-link  <?php echo current_nav() == 'orders_reports' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-file-invoice text-sm"></i>
                                        <p><?= trans('menu_orders_reports') ?></p>

                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_due_reports')): ?>
                                <li class="nav-item">
                                    <a href="due_reports.php"
                                        class="nav-link  <?php echo current_nav() == 'due_reports' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-hourglass-half text-sm"></i>
                                        <p><?= trans('menu_due_reports') ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_stock_reports')): ?>
                                <li class="nav-item">
                                    <a href="stock_reports.php"
                                        class="nav-link  <?php echo current_nav() == 'stock_reports' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-boxes text-sm"></i>
                                        <p><?= trans('menu_stock_reports') ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_missing_reports')): ?>
                                <li class="nav-item">
                                    <a href="missing_reports.php"
                                        class="nav-link  <?php echo current_nav() == 'missing_reports' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-exclamation-circle text-sm"></i>
                                        <p><?= trans('menu_missing_reports') ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if (user_group_id() == 1 || has_permission('access', 'read_cash_book')): ?>
                                <li class="nav-item">
                                    <a href="cash_book.php"
                                        class="nav-link  <?php echo current_nav() == 'cash_book' ? ' active' : null; ?>">
                                        <span class="line"><i class="fas fa-minus"></i></span>
                                        <i class="nav-icon fas fa-money-bill-wave text-sm"></i>
                                        <p><?= trans('menu_cash_book') ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>