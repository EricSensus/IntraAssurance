<?php
use Jenga\App\Request\Url;

$pesa = $data->payments;
include_once PROJECT_PATH .DS. 'forms' .DS. 'wizard' .DS. 'wizard.php';
?>

<div class="insuranceheader row">
    <div class="insurance col-md-12 col-sm-12 col-xs-12">
        <h2>Motor and MotorCycle Insurance</h2>
    </div>
</div>

<?php
    echo wizardHTML('motor','4');
?>

<div class="col-xs-12">
    <div class="col-md-12">
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
                                <?php if ($key == 'date_of_birth' || $key=='regdate'):
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
                    <?php
                    foreach (get_object_vars($pesa->main_entity) as $key => $show):?>
                        <?php if ($key == 'entity_values'): ?>
                            <tr>
                                <td><?= ucwords($key) ?></td>
                                <td>
                                    <?php if ($key == 'date_of_birth'):
                                        echo date('d/m/Y', $show);
                                    elseif ($key == 'entity_values'):
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
                        endif;
                    endforeach;
                    ?>

                    <?php
                    foreach (get_object_vars($pesa->quote) as $key => $show):?>
                        <?php if ($key == 'entity_values'): ?>
                            <tr>
                                <td><?= ucwords($key) ?></td>
                                <td>
                                    <?php if ($key == 'date_of_birth'):
                                        echo date('d/m/Y', $show);
                                    elseif ($key == 'product_info'):
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
                        endif;
                    endforeach;
                    ?>
                </table>
                <button class="btn btn-success"><i class="fa fa-arrow-right"></i> Checkout</button>
            </div>
        </div>
    </div>
</div>

