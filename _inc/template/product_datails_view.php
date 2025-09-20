<div class="card-body p-2">
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_name") ?></th>
                    <th class="col-6 text-right pr-2 py-2 border-bottom"><?php echo $product['p_name'] ?></th>
                </tr>
                <tr>
                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_barcode_/_QR_code") ?></th>
                    <th class="col-6 text-right pr-2 py-2 border-bottom"><?php echo $product['p_code'] ?></th>
                </tr>
                <tr>
                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_material") ?></th>
                    <th class="col-6 text-right pr-2 py-2 border-bottom"><?php echo $material ?></th>
                </tr>
                <tr>
                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_weight") ?></th>
                    <th class="col-6 text-right pr-2 py-2 border-bottom"><?php echo $product['wgt'] ?></th>
                </tr>
                <tr>
                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_category") ?></th>
                    <th class="col-6 text-right pr-2 py-2 border-bottom"><?php echo $category ?></th>
                </tr>
                <tr>
                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_supplier") ?></th>
                    <th class="col-6 text-right pr-2 py-2 border-bottom"><?php echo $supplier['s_name'] ?></th>
                </tr>
                <tr>
                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_cost") ?></th>
                    <th class="col-6 text-right pr-2 py-2 border-bottom">Rs. <?php echo number_format($product['cost'], 2) ?></th>
                </tr>
                <tr>
                    <th class="col-6 pl-2 py-2 border-bottom" style="vertical-align: middle;"><?php echo trans("label_created_at") ?></th>
                    <th class="col-6 text-right pr-2 py-2 border-bottom">
                        <div>
                            <?php echo  date("d M, Y", strtotime($product['created_at'])) ?>
                        </div>
                        <div>
                            <?php echo  date("h:i a", strtotime($product['created_at'])) ?>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_status") ?></th>
                    <th class="col-6 text-right pr-2 py-2 border-bottom"><?php echo $status ?></th>
                </tr>

                <?php if ($product['status'] == 3) { ?>
                    <tr>
                        <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_invoice_no") ?></th>
                        <th class="col-6 text-right pr-2 py-2 border-bottom"><?php echo $invoice['invoice_no'] ?></th>
                    </tr>
                    <tr>
                        <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_ref_/_bill_no") ?></th>
                        <th class="col-6 text-right pr-2 py-2 border-bottom"><?php echo $invoice['ref_no'] ?></th>
                    </tr>
                    <tr>
                        <th class="col-6 pl-2 border-bottom" style="vertical-align: middle;"><?php echo trans("label_customer") ?></th>
                        <th class="col-6 pr-2 text-right py-2 border-bottom">
                            <div class="p-0"><?php echo $invoice['cus_name'] ?></div>
                            <div class="p-0"><?php echo format_mobile($invoice['cus_mobile']) ?></div>
                            <div class="p-0"><?php echo $invoice['cus_address'] ?></div>
                        </th>
                    </tr>
                    <tr>
                        <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_item_price") ?></th>
                        <th class="col-6 text-right pr-2 py-2 border-bottom">Rs. <?php echo number_format($invoice['sub_total'], 2) ?></th>
                    </tr>
                    <tr>
                        <th class="col-6 pl-2 py-2 border-bottom" style="vertical-align: middle;"><?php echo trans("label_sold_at") ?></th>
                        <th class="col-6 text-right pr-2 py-2 border-bottom">
                            <div>
                                <?php echo  date("d M, Y", strtotime($invoice['invoice_date'])) ?>
                            </div>
                            <div>
                                <?php echo  date("h:i a", strtotime($invoice['invoice_date'])) ?>
                            </div>
                        </th>
                    </tr>
                <?php } ?>
            </thead>
        </table>
    </div>
</div>