<?php

//print_r(get_defined_vars());exit;
?>
<div class="row">
    <div class="form-group">
        <?php
        echo $label_customer_id . $customer_id
        ?>
    </div>
</div>
<?php
if (!isset($policies)) {
    ?>
    <div class="select-quote">
    </div>
    <?php
} else {
    ?>
    <div class="select-quote row even">
        <?php
        echo $label_customer_id . $policies;
        ?>
    </div>
    <?php
}
?>
<div class="row last">
    <?php
    echo $btnsubmit
    ?>
</div>
