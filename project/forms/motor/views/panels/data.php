<?php
use Jenga\App\Request\Url;

$pesa = $data->payments;
?>

<div class="row form-group">
    <div class="col-xs-12">
        <ul class="nav nav-pills nav-justified thumbnail setup-panel">
            <li class="disabled"><a href="<?= Url::link('motor/step') ?>">
                    <h4 class="list-group-item-heading">Step 1</h4>
                    <p class="list-group-item-text">Proper personal details</p>
                </a>
            </li>
            <li class="disabled"><a href="<?= Url::link('motor/step/2') ?>">
                    <h4 class="list-group-item-heading">Step 2</h4>
                    <p class="list-group-item-text">Car Details</p>
                </a>
            </li>
            <li class="disabled"><a href="<?= Url::link('motor/step/3') ?>">
                    <h4 class="list-group-item-heading">Step 3</h4>
                    <p class="list-group-item-text">Cover Details</p>
                </a>
            </li>
            <li class="active"><a href="<?= Url::link('motor/step/4') ?>">
                    <h4 class="list-group-item-heading">Step 4</h4>
                    <p class="list-group-item-text">Quotation and Payment</p>
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="panel-body" id="step-1">
    <div class="col-xs-12">
        <div class="col-md-12 well">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Name: <?= $pesa->customer->name ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-condensed">
                        <?php
                        foreach (get_object_vars($pesa->customer) as $key => $show):?>
                            <tr>
                                <td><?= ucwords($key) ?></td>
                                <td>
                                    <?php if ($key == 'date_of_birth'):
                                        echo date('d/m/Y', $show);
                                    elseif ($key == 'additional_info'):
                                        ?>
                                        <dl class="dl-horizontal">
                                            <?php
                                            foreach (json_decode($show) as $k => $v):
                                                ?>
                                                <dt><?= ucwords($k) ?></dt>
                                                <dd><?= $v ?></dd>
                                                <?php
                                            endforeach;
                                            ?>
                                        </dl>
                                        <?php
                                    else:
                                        echo $show;
                                    endif; ?>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                        ?>
                    </table>
                    <button class="btn btn-success"><i class="fa fa-arrow-right"></i> Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>