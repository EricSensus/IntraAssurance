<?php
use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;

HTML::head(TRUE, TRUE);
HTML::css('admin/css/admin_css.css', FALSE, TRUE);
HTML::css('preview/css/preview.css', FALSE, TRUE);
?>
<div class="container" style="background-color: #fff;">
    <div class="row">
        <div class="col-xs-12">
            <div class="invoice-title">
                <h2 style="display: inline-block;">Policy: <span><?=$policy->policy_number; ?></span></h2>
                <a href="<?=Url::link('/admin/policies/downloaddocs/'.$policy->id); ?>"
                   class="btn btn-info pull-right <?=$class; ?>" style="margin-top: 20px;" data-toggle="modal" data-target="#download-docs">
                    <i class="fa fa-download"></i> Download/View Related Documents
                </a>
            </div>
            <hr>
            <div class="row">
                <div class="col-xs-6">
                    <address>
                        <strong>Quote Reference Number: </strong> <?=$quote->id; ?><br>
                        <strong>Customer: </strong><?=$customer->surname.' '.$customer->names; ?><br>
                        <strong>Email Address: </strong><?=$customer->email; ?><br>
                        <strong>Insurer: </strong><?=$insurer['name']; ?>
                    </address>
                </div>
                <div class="col-xs-6 <?=(!empty($class)) ? '' : 'text-right'; ?>">
                    <strong>
                        <strong>Date Generated: </strong><?=$policy->dategenerated; ?><br>
                        <strong>Policy Number: </strong><?=$policy->policy_number; ?><br>
                        <strong>Product: </strong><?=$policy->product; ?>
                    </strong>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6">
                    <address>
                        <h4>Validity</span></h4>
                        <blockquote>
                            <span style="font-size: <?=(empty($class)) ? '15px' : 'small'; ?>">
                                Start Date: <b><?=date('d F Y', $policy->start_date); ?></b>
                                End Date: <b><?=date('d F Y', $policy->end_date); ?></b>
                            </span>
                        </blockquote>
                    </address>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Coverage - Product Details</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <?=$product_details; ?>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Calculations</strong></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>

                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <?=$core; ?>

                                <?php
                                    if (!empty($other_covers)){
                                ?>
                                <tr><td colspan="2">&nbsp;</td></tr>
                                <tr><th colspan="2"><h4>Additional Coverage</h4></th></tr>
                                <?php } ?>

                            </thead>
                            <tbody>
                                <?=$other_covers; ?>
                                <tr><td colspan="2">&nbsp;</td></tr>
                                <?=$more; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><h3>Total:</h3></th>
                                    <th><h3>Ksh. <?=number_format($amounts->total,2); ?></h3></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Modal-->
<?=$download_docs_modal; ?>