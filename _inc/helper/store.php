<?php
// function get_store_id_by_code($code) 
// {
// 	$model = registry()->get('loader')->model('store');
// 	return $model->getStoreIdByCode($code);
// }

function store($field = null) 
{
	global $store;
	if (!$field) {
		return $store->getAll();
	}
	return $store->get($field);
}

function store_id() 
{
	global $store;
	return $store->get('store_id');
}

function get_preference($index = null, $store_id = null)
{
	$store_id = $store_id ? $store_id : store_id();
	$storeModel = registry()->get('loader')->model('store');
	$store = $storeModel->getStore($store_id);
	$preference = unserialize($store['preference']);
	return isset($preference[$index]) ? $preference[$index] : null;
}

function get_all_preference($store_id = null) 
{
	$store_id = $store_id ? $store_id : store_id();
	$storeModel = registry()->get('loader')->model('store');
	$store = $storeModel->getStore($store_id);
	$preference = unserialize($store['preference']);
	return $preference;
}

function get_stores($all = false) 
{
	global $user;
	if ($all || user_group_id() == 1) {
		$storeModel = registry()->get('loader')->model('store');
		return $storeModel->getStores();
	} else {
		return $user->getBelongsStore(user_id());
	}
}
function store_field($index, $store_id = null) 
{
	$store_id = $store_id ? $store_id : store_id();
	$store = registry()->get('loader')->model('store');
	$store = $store->getStore($store_id);
	return isset($store[$index]) ? $store[$index] : null;
}
