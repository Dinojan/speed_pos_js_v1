<?php
include("../_init.php");
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle('Users');
$document->setController('UserController');
?>
<?php include 'src/_top.php'; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
            <div class="card-header">
                <h3 class="card-title"><?= trans('title_users') ?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" ng-click="openAddMobileModal()">
                        <i class="fas fa-plus"></i> <?= trans('button_add_user') ?>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <?php
                    $hide_colums = "";
                    if (user_group_id() != 1) {
                        if (!has_permission('access', 'update_user')) {
                            $hide_colums .= "3,";
                        }
                        if (!has_permission('access', 'delete_user')) {
                            $hide_colums .= "4,";
                        }
                    }
                    ?>

                    <table class="table table-bordered table-striped table-sm table-hover"
                        data-hide-colums="<?php echo $hide_colums; ?>" id="userTable">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left"><?= trans('text_name') ?></th>
                                <th class="text-center"><?= trans('text_user_group') ?></th>
                                <th class="text-center"><?= trans('text_email') ?></th>
                                <th class="text-center"><?= trans('text_mobile') ?></th>
                                <th class="text-center"><?= trans('text_view') ?></th>
                                <th class="text-center"><?= trans('text_edit') ?></th>
                                <th class="text-center"><?= trans('text_delete') ?></th>
                            </tr>
                        </thead>

                        <tfoot class="bg-primary text-white">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left"><?= trans('text_name') ?></th>
                                <th class="text-center"><?= trans('text_user_group') ?></th>
                                <th class="text-center"><?= trans('text_email') ?></th>
                                <th class="text-center"><?= trans('text_mobile') ?></th>
                                <th class="text-center"><?= trans('text_view') ?></th>
                                <th class="text-center"><?= trans('text_edit') ?></th>
                                <th class="text-center"><?= trans('text_delete') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>


<?php include 'src/_end.php'; ?>