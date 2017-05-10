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
            <span class="small" style="color: red;">Type to search for customer's name</span>
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
<?php
    if(isset($_GET['policy'])){
?>
<input type="hidden" name="policy" value="<?=$_GET['policy']; ?>"/>
<?php } ?>
<div class="buttons">
    <div class="row last">
        <?php
        echo $btnsubmit
        ?>
    </div>
</div>