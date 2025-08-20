
 <?php 
function get_all_customers($sts){
    $modal = registry()->get('loader')->model('customer');
    return $modal->getcustomers($sts);
}
