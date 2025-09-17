<form id="hold-order-form" class="form-horizontal pt-3 pb-4" onsubmit="return false" metdod="post"
    enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="HOLD">

    <div class="pb-4 px-3">
        <div class="">
            <input type="text" id="hold_ref_no_input" name="hold_ref_no" class="form-control" placeholder="Order reference or bill number" ng-model="hold_ref_no">
        </div>
        <div class="text-danger text-center"></div>
    </div>

    <center>
        <h5>Order Details</h5>
    </center>

    <div class="card-body">
        <div class="table-responsive">
            <table class="col-12 table table-bordered">
                <tdead>
                    <tr ng-repeat="item in cart" class="bg-light font-weight-bold">
                        <td class="col-1 py-2" style="border-bottom: none;">{{$index + 1}}</td>
                        <td class="col-8 py-2 text-start" style="border-bottom: none;">
                            <div>{{ item.p_name }} - {{ item.material_name }} - {{ item.qty }} Piece(s)</div>
                            <div>Rs. {{ item.material_price | number: 2 }} - Rs. {{ item.discount | number: 2 }} Discount</div>
                        </td>
                        <td class="col-3 py-2 text-right pr-3 py-1" style="border-bottom: none;">Rs. {{ item.sub_total | number: 2 }}</td>
                    </tr>
                </tdead>
                <tbody style="border-top: none;">
                    <tr>
                        <td colspan="2" class="pl-3 py-1">Sub total</td>
                        <td class="text-right pr-3 py-1">{{ getTotal() | number: 2 }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pl-3 py-1">Total discount</td>
                        <td class="text-right pr-3 py-1">{{ getTotalDiscount() | number: 2 }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="pl-3 py-1">Final payable amount({{ getTotalItems() }} of {{ cart.lengtd }})</td>
                        <td class="text-right pr-3 py-1">{{ getFinalAmount() | number: 2 }}</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" class="btn btn-warning" id="hold_order_btn" ng-click="submitHoldOrder()">
                <i class="fas fa-pause"></i> Hold order
            </button>
        </div>
    </div>
</form>