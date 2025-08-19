<?php
function is_loggedin()
{
	global $user;
	return $user->isLogged();
}

function is_supper_admin()
{
	global $user;
	return user_group_id() == 1;
}
function is_admin()
{
	global $user;
	return user_group_id() == 2;
}

function user($field) 
{
	return get_the_user(user_id(), $field);
}

function user_id() 
{
	global $user;
	return $user->getId();
}

function user_group_id() 
{
	global $user;
	return $user->getGroupId();
}

function get_users() 
{
	
	$model = registry()->get('loader')->model('user');
	return $model->getUsers();
}

function get_the_user($id, $field = null) 
{
	
	$model = registry()->get('loader')->model('user');
	$user = $model->getUser($id);
	if ($field && isset($user[$field])) {
		return $user[$field];
	} elseif ($field) {
		return;
	}
	return $user;
}


function user_avatar($sex)
{
	
	$user_model = registry()->get('loader')->model('user');
	return $user_model->getAvatar($sex);
}

function has_permission($type, $param)
{
	global $user;
	return $user->hasPermission($type, $param);
}

function get_total_users(){
	$user_model = registry()->get('loader')->model('user');
	return $user_model->getTotalUsers();
}

function is_department_manager() {
    $user_model = registry()->get('loader')->model('user');
    return $user_model->isDepartmentManager(user_id());
}

function get_users_with_role($role_id) {
    $user_model = registry()->get('loader')->model('user');
    return $user_model->getUserWdRole($role_id);
}
