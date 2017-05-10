<div class="row"><div class="col-md-12">
        <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                <h4 class="mb5 text-light"><i class="glyphicon glyphicon-refresh"></i> Renew Policies</h4>
<!--                <p class="small"><strong>Manage</strong> Your Policies</p>-->
            </div>
            <div class="right toolholder"></div>
        </div>
    </div>
</div>

<div class="row show-grid">
    <div class="col-md-12 panel">
        <div class="dash-head clearfix mt15 mb20">

            <form action="<?=\Jenga\App\Request\Url::link('/admin/policies/renewPolicies'); ?>" method="post" class="form-horizontal">
                <?php
                    $i = 1;
                    if(count($policies)){
                        foreach ($policies as $policy){

                ?>
                    <div class="form-group">
                        <h4 class="control-label col-sm-3">Policy#: <?=$policy['policy_number']; ?></h4>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="end_date">End Date:</label>
                        <div class="col-sm-6">
                            <input type="text" name="end_date<?=$i; ?>" value="<?=$policy['end_date']; ?>"
                                   class="form-control" id="end_date" placeholder="End Date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="period">Period</label>
                        <div class="col-sm-6">
                            <select name="period<?=$i; ?>" class="form-control">
                                <option value="">--Choose Period--</option>
                                <option value="3 month">3 Months</option>
                                <option value="6 month">6 Months</option>
                                <option value="1 year">1 Year</option>
                            </select>
                        </div>
                    </div>
                    <hr/>
                    <input type="hidden" name="policy_id<?=$i; ?>" value="<?=$policy['policy_id']; ?>"/>
                <?php $i++; }} ?>

                <input type="hidden" name="field_count" value="<?=$i - 1; ?>"/>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i> Process</button>
                    </div>
                </div>
            </form>

        </div>

        <div class="dataTables_wrapper panel-footer">
            <div id="policies_footer"></div>
        </div>
    </div>
</div>

