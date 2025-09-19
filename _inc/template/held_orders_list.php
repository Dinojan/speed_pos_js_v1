<div class="card-body p-0 p-lg-2">
    <div class="col-lg-4 p-0">
        <input type="text" class="form-control" id="search_order" placeholder="Search order..." ng-model="searchOrder" ng-change="getOrder()">
        <div class="mt-4 border rounded d-flex align-items-center" style="overflow: hidden;">
            <div class="m-0 p-2">
                <p><i class="fas fa-angle-double-right m-0"></i> <span>{{ order.ref_no }} - {{ order.cus_name }}</span></p>
                <button class=""><i class="fas fa-trash text-danger"></i></button>
            </div>
        </div>
    </div>
    <div class="col-lg-8 p-0">

    </div>
</div>