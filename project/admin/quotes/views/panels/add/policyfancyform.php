<table class="policy table-striped">
    <tr>
        <td class="heading" colspan="2">
            <h2 class="mb5 text-light">Quote Generation</h2>
        </td>
    </tr>
    <tr>
        <td>
            <h4 class="status">
                Status
            </h4>
        </td>
        <td class="field">
            <h4><strong>
                    <?php
                    echo $status;
                    ?>
                </strong></h4>
        </td>
    </tr>
    <tr>
        <td width="25%">
            <?php
            echo '<h4>' . $label_dategen . '</h4>';
            ?>
        </td>
        <td class="field" width="75%">
            <?php
            echo '<div class="data">' . $dategen . '</div>';
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php
            echo '<h4>' . $label_agent . '</h4>';
            ?>
        </td>
        <td class="field">
            <?php
            echo '<div class="data">' . $agent . '</div>';
            ?>
        </td>
    </tr>

</table>
<table class="policy table-striped">
    <tr>
        <td class="heading" colspan="2">
            <h2 class="mb5 text-light">Customer Information</h2>
        </td>
    </tr>
    <tr>
        <td width="25%">
            <?php
            echo '<h4>' . $label_customer . '</h4>';
            ?>
        </td>
        <td class="field" width="75%">
            <?php
            echo '<div class="data">' . $customer . '</div>';
            ?>
<!--            <span class="small" style="color: red;">Type to search for customer's name</span>-->
        </td>
    </tr>
    <tr>
        <td>
            <?php
            echo '<h4>' . $label_email . '</h4>';
            ?>
        </td>
        <td class="field">
            <?php
            echo '<div class="data">' . $email . '</div>';
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php
            echo '<h4>' . $label_phone . '</h4>';
            ?>
        </td>
        <td class="field">
            <?php
            echo '<div class="data">' . $phone . '</div>';
            ?>
        </td>
    </tr>
</table>
<table class="policy product-details table-striped">
    <tr>
        <td class="heading" colspan="2">
            <h2 class="mb5 text-light">Product Information</h2>
        </td>
    </tr>
    <tr>
        <td width="25%">
            <?php
            echo '<h4>' . $label_product . '</h4>';
            ?>
        </td>
        <td class="field" width="75%">
            <?php
            echo '<div class="data">' . $product . '</div>';
            ?>
        </td>
    </tr>
</table>
<div class="ajaxresponse1" style="width:100%; text-align: center;"></div>

<table class="policy entity-details table-striped">
    <tr>
        <td class="heading" colspan="3">
            <h2 class="mb5 text-light">Customer Entity Information</h2>
        </td>
    </tr>
    <tr>
        <td width="25%">
            <label id="label_entity">Select Entity<span class="required">*</span></label>
        </td>
        <td width="75%" class="field">
            <div class="ajaxresponse2" style="width:80%; text-align: left; float: left;"></div>
            <button type="button" class="btn btn-default addentity">Add Entity</button>
        </td>
    </tr>
</table>
<div class="ajaxresponse3" style="width:100%; text-align: left;">

</div>

<div id="quote-preview">

</div>
</table>
<button id="previewer" type="button" class="btn btn-success btn-xs">Preview Quote</button>
<div class="policy pricing-details table-striped">

</div>
<div class="buttons">
    <div class="row last">
        <?php
        echo $btnsubmit
        ?>
    </div>
</div>