<style>


</style>
<div class="modal fade" id="dateFilterModal" tabindex="-1" aria-labelledby="dateFilterModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-lg">
            <div class="modal-body text-center mx-3">
                <form action="" method="get">
                    <div class="row">
                        <?php if (!empty($request->get)) : ?>
                            <?php foreach ($request->get as $key => $value) : ?>
                                <?php if (!in_array($key, array('from', 'to'))) : ?>
                                    <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="well rounded mt-5">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-2 p-2">
                                <a href="<?php echo relative_url(); ?><?php echo $query_string ? $query_string . '&' : '?'; ?>ftype=today&from=<?php echo date('Y-m-d'); ?>&to=<?php echo date('Y-m-d'); ?>" class="btn btn-primary btn-sm btn-block rounded" <?php echo isset($ftype) && $ftype == 'today' ? 'style="border:3px solid blue;"' : ''; ?>><?php echo trans('button_today'); ?></a>
                            </div>
                            <div class="col-md-2 p-2">
                                <a href="<?php echo relative_url(); ?><?php echo $query_string ? $query_string . '&' : '?'; ?>ftype=week&from=<?php echo date('Y-m-d', strtotime("-7 days", time())); ?>&to=<?php echo date('Y-m-d'); ?>" class="btn btn-success btn-sm btn-block rounded" <?php echo isset($ftype) && $ftype == 'week' ? 'style="border:3px solid green;"' : ''; ?>><?php echo trans('button_last_7_days'); ?></a>
                            </div>
                            <div class="col-md-2 p-2">
                                <a href="<?php echo relative_url(); ?><?php echo $query_string ? $query_string . '&' : '?'; ?>ftype=month&from=<?php echo date('Y-m-d', strtotime("-30 days", time())); ?>&to=<?php echo date('Y-m-d'); ?>" class="btn btn-warning btn-sm btn-block rounded" <?php echo isset($ftype) && $ftype == 'month' ? 'style="border:3px solid yellow;"' : ''; ?>><?php echo trans('button_last_30_days'); ?></a>
                            </div>
                            <div class="col-md-2 p-2">
                                <a href="<?php echo relative_url(); ?><?php echo $query_string ? $query_string . '&' : '?'; ?>ftype=year&from=<?php echo date('Y-m-d', strtotime("-365 days", time())); ?>&to=<?php echo date('Y-m-d'); ?>" class="btn btn-info btn-sm  btn-block rounded" <?php echo isset($ftype) && $ftype == 'year' ? 'style="border:3px solid blue;"' : ''; ?>><?php echo trans('button_last_365_days'); ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5 mb-5">
                        <div class="col-md-4 mx-auto">
                            <label for="from" class="text-left" style="width: 100%;">From:</label>
                            <input type="date" class="form-control rounded" name="from" id="from" value="<?php echo isset($request->get['from']) ? $request->get['from'] : null; ?>" placeholder="From">

                        </div>
                        <div class="col-md-4 mx-auto">
                            <label for="to" class="text-left" style="width: 100%;">To:</label>
                            <input type="date" class="form-control rounded" name="to" id="to" value="<?php echo isset($request->get['to']) ? $request->get['to'] : null; ?>">

                        </div>
                        <div class="col-md-4 mx-auto mt-4">
                            <button class="btn btn-block btn-md btn-success rounded mt-1" type="submit">
                                <span class="far fa-calendar-alt mr-1"></span> <?php echo trans('button_filter'); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- <div class="modal-footer mx-auto">

                <div class="filter_close" data-dismiss="modal">
                    <strong class="text-lg"><i class="fas fa-times"></i></strong>
                </div>
            </div> -->
        </div>
    </div>
</div>
<script type="text/javascript">
    var from = "<?php echo from() ? format_date(from()) : format_date(date('Y/m/d')); ?>";
    var to = "<?php echo to() ? format_date(to()) : format_date(date('Y/m/d')); ?>";
</script>
<footer class="main-footer <?= style_class('footerClass') ?>" style="position: fixed; bottom: 0; left: 0; width: 100vw;"><b>
        <?php echo trans('text_powered_by') ?> <a href="<?= $appConfig['CopyrightsUrl'] ?>" class="text-gray" target="_blank"><?= strtoupper($appConfig['Copyrights']) ?></a> </b>
    <div class="float-right d-none d-sm-inline-block">
        <b><?php echo trans('text_version') ?></b> <?= $appConfig['Version'] ?>
    </div>
</footer>