<?php
include("../_init.php");
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle('Password');
$document->setController('PasswordController');

// USER MODEL 
$user_model = registry()->get('loader')->model('user');

// FETCH ALL USER 
$users = $user_model->getUsers();

if (isset($request->post['form_change_password'])) {
    try {

        // cCheck Update Permission
        if (user_group_id() != 1 && !has_permission('access', 'change_password')) {
            throw new Exception(trans('error_password_permission'));
        }

        // Fetch User
        $the_user = $user_model->getUser($request->post['user_id']);
        if (!isset($the_user['id'])) {
            throw new Exception(trans('error_password_user_found'));
        }

        if (user_group_id() != 1) {

            // Old Passwod Validation
            if (empty($request->post['old'])) {
                throw new Exception(trans('error_password_old'));
            }

            // Fetch User
            $old_password = md5($request->post['old']);

            // Check Old Passwrod
            if ($old_password != $the_user['password']) {
                throw new Exception(trans('error_password_old_wrong'));
            }
        }

        // New Password Validation
        if (empty($request->post['new1'])) {
            throw new Exception(trans('error_password_new'));
        }

        // Confirm Password Validation
        if (empty($request->post['new2'])) {
            throw new Exception(trans('error_password_old'));
        }

        // Matching New and Confirm Password
        if ($request->post['new1'] != $request->post['new2']) {
            throw new Exception(trans('error_password_mismatch'));
        }

        // Check password strongness
        if (($errMsg = checkPasswordStrongness($request->post['new1'])) != 'ok') {
            throw new Exception($errMsg);
        }

        $new_final_password = md5($request->post['new1']);

        // Updating Password
        $statement = $db->prepare("UPDATE `users` SET `password` = ?, `raw_password` = ? WHERE `id` = ?");
        $statement->execute(array($new_final_password, $request->post['new1'], $the_user['id']));
        $success_message = trans('text_success');
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>
<?php include 'src/_top.php'; ?>
<div class="row">
    <div class="col-12">

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <p>
                    <i class="fa fa-warning"></i>
                    <?php echo $error_message; ?>
                </p>
            </div>
        <?php elseif (isset($success_message)): ?>
            <div class="alert alert-success">
                <p>
                    <i class="fa fa-check"></i>
                    <?php echo $success_message; ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="card card-primary card-outline btn" data-toggle="modal" data-target="#passChangeModal">
            <div class="card-body text-center">
                <span class="text-danger" style="font-size: 8rem;">
                    <i class="fa fa-unlock fa-flip"></i>
                </span>
                <br>
                <i><?php echo trans('text_password_change'); ?> 👆</i>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="passChangeModal" tabindex="-1" role="dialog" aria-labelledby="passChangeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="passChangeModalLabel">
                    <i class="fa fa-lock mr-2"></i> <?php echo trans('title_password'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="row" action="" method="post" enctype="multipart/form-data">

                    <?php if (user_group_id() == 1): ?>
                        <div class="col-12">
                            <label for="user_id" class="form-label"><?php echo trans('label_user_name'); ?></label>
                            <select name="user_id" id="user_id" class="custom-select select2bs4">
                                <?php foreach ($users as $the_user): ?>
                                    <option value="<?php echo $the_user['id']; ?>" <?php echo $the_user['id'] == $user->getId() ? 'selected' : null; ?>>
                                        <?php echo $the_user['username'] . ' (' . $the_user['email'] . ')'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <?php foreach ($users as $the_user): ?>
                            <?php if ($the_user['id'] == $user->getId()): ?>
                                <input type="hidden" name="user_id" id="user_id" value="<?php echo $the_user['id']; ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (user_group_id() != 1): ?>
                        <div class="col-12">
                            <label for="old" class="form-label"><?php echo trans('label_password_old'); ?></label>
                            <input type="password" class="form-control" id="old" name="old" required>
                        </div>
                    <?php endif; ?>

                    <div class="col-12">
                        <label for="new1" class="form-label"><?php echo trans('label_password_new'); ?></label>
                        <input type="password" class="form-control" id="new1" name="new1" required>
                    </div>

                    <div class="col-12">
                        <label for="new2" class="form-label"><?php echo trans('label_password_confirm'); ?></label>
                        <input type="password" class="form-control" id="new2" name="new2" required>
                    </div>

                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-info" name="form_change_password">
                            <i class="fas fa-edit mr-1"></i>
                            <?php echo trans('button_update'); ?>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>


<?php include 'src/_end.php'; ?>