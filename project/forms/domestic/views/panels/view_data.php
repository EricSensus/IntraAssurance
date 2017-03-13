<?php
use Jenga\App\Request\Url;

$quotation = $data->quotation;
$more = json_decode($quotation->customer->additional_info);

?>

<div class="row setup-content" id="step-1">
    <div class="col-xs-12">
        <div class="col-md-12 well">
            <!--Personal Details-->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Personal Details: <?= $quotation->customer->name ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <td>Mobile No:</td>
                                <td><?=$quotation->customer->mobile_no; ?></td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td><?=$quotation->customer->email; ?></td>
                            </tr>
                            <tr>
                                <td>Postal Address:</td>
                                <td><?=$quotation->customer->postal_address; ?></td>
                            </tr>
                            <tr>
                                <td>Postal Code:</td>
                                <td><?=$quotation->customer->postal_code; ?></td>
                            </tr>
                            <tr>
                                <td>Registration Date:</td>
                                <td><?=date('Y-M-d', $quotation->customer->regdate); ?></td>
                            </tr>
                            <tr>
                                <td>Pin:</td>
                                <td><?=$more->pin; ?></td>
                            </tr>
                            <tr>
                                <td>Id No:</td>
                                <td><?=$more->idnumber; ?></td>
                            </tr>
                            <tr>
                                <td>Street:</td>
                                <td><?=$more->street; ?></td>
                            </tr>
                            <tr>
                                <td>Town:</td>
                                <td><?=$more->town; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!--Property Details-->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Details</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <?php
                            $entity_values = json_decode($quotation->main_entity->entity_values);
                            if(count($entity_values)){
                                foreach ($entity_values as $label => $entity_value){
                        ?>
                            <tr>
                                <td><?=str_replace('_', ' ', ucwords($label)); ?></td>
                                <td><?=$entity_value; ?></td>
                            </tr>
                        <?php }} ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!--Cover Details-->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Cover Details</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                        <?php
                        $products = json_decode($quotation->quote->product_info);
                        if(count($products)){
                            foreach ($products as $label => $product){
                                ?>
                                <tr>
                                    <td><?=str_replace('_', ' ', ucwords($label)); ?></td>
                                    <td><?=$product; ?></td>
                                </tr>
                            <?php }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>