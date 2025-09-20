<div class="card-body p-2">
    <div class="table-resposive">
        <table class="table table-sm table-bordered table-striped">
            <thead>
                <tr>
                    <th class="col-3 pl-3 py-2" style="border-bottom: none;"><?php echo trans("label_jewelry_code") ?></th>
                    <th class="col-3 text-right pr-3 py-2" style="border-bottom: none;"><?php echo $product['p_code'] ?></th>
                    <th class="col-3 pl-3 py-2" style="border-bottom: none;"><?php echo trans("label_name") ?></th>
                    <th class="col-3 text-right pr-3 py-2" style="border-bottom: none;"><?php echo $product['p_name'] ?></th>
                </tr>
                <tr>
                    <th class="col-3 pl-3 py-2" style="border-bottom: none;"><?php echo trans("label_material") ?></th>
                    <th class="col-3 text-right pr-3 py-2" style="border-bottom: none;"><?php echo  $material ?></th>
                    <th class="col-3 pl-3 py-2" style="border-bottom: none;"><?php echo trans("label_karate") ?></th>
                    <th class="col-3 text-right pr-3 py-2" style="border-bottom: none;"><?php echo $product['karate'] ? $product['karate'] . ' karate' : "Not provided" ?></th>
                </tr>
                <tr>
                    <th class="col-3 pl-3 py-2" style="border-bottom: none;"><?php echo trans("label_cost") ?></th>
                    <th class="col-3 text-right pr-3 py-2" style="border-bottom: none;"><?php echo $product['cost'] ?></th>
                    <th class="col-3 pl-3 py-2" style="border-bottom: none;"><?php echo trans("label_supplire") ?></th>
                    <th class="col-3 text-right pr-3 py-2" style="border-bottom: none;"><?php echo $supplier['s_name'] ?></th>
                </tr>
                <tr>
                    <th class="col-3 pl-3 py-2" style="border-bottom: none;"><?php echo trans("label_weight") ?></th>
                    <th class="col-3 text-right pr-3 py-2" style="border-bottom: none;"><?php echo $product['wgt'] ?>g</th>
                    <th class="col-3 pl-3 py-2" style="border-bottom: none;"><?php echo trans("label_current_status") ?></th>
                    <th class="col-3 text-right pr-3 py-2" style="border-bottom: none;"><?php echo $status ?></th>
                </tr>
                <tr>
                    <th colspan="2" class=" pl-3 py-2" style="border-bottom: none;"><?php echo trans("label_last_checked") ?></th>
                    <th colspan="2" class="pl-3 py-2" style="border-bottom: none;"><?php echo $last_checked ?></th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="card-header px-0">
        <h5><?php echo trans("label_history") ?></h5>
    </div>

    <div class="card-body p-0" style="max-height: 50vh;">
        <div class="table responsive">
            <?php if (!empty($history)) { ?>
                <table id="stock-checking-history" class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr class="bg-primary">
                            <th class="text-center py-1 px-0"><?php echo trans("label_no.") ?></th>
                            <th class="text-center py-1"><?php echo trans("label_date") ?></th>
                            <th class="pl-3 py-1"><?php echo trans("label_checker") ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $index => $row) : ?>
                            <tr>
                                <td class="text-center py-1 px-0"><?php echo $index + 1 ?></td>
                                <td class="text-center py-1"><?php echo date("d F, Y / h:i a", strtotime($row['created_at'])) ?></td>
                                <td class="pl-3 py-1"><?php echo get_the_user($row['checked_by'], 'username') ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <h6 class="col-12 text-center text-danger py-4"><?php echo trans("label_--_checked_history_not_found_--") ?></h6>
            <?php } ?>
        </div>
    </div>
</div>

<?php ?>