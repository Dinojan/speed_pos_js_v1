<?php 
function get_user_groups(){
    $usergroup_model = registry()->get('loader')->model('usergroup');
    return $usergroup_model->getUserGroups();
}