<?php
use Jenga\App\Views\HTML;
use Jenga\App\Views\Overlays;
use Jenga\App\Request\Url;

echo '<div class="row">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">Add New Insurance Policy</h4>
                    <p class="small"><strong>Add</strong> New Policy</p>
            </div>';
echo '</div>
</div>
</div>';

echo '<div class="row margin-top-10">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">';

echo '<div class="col-xs-12">';

echo $policyguide;

echo '</div>';

echo '<div class="col-xs-12">';
if(isset($current_step)) {
    $url = Url::route('/admin/documents/upload/{element}/{action}/{id}/{folder}',
        ['element' => 'policies','action' => 'edit','id' => $id,'folder'=>'documents']);

    echo '<div class="panel-heading">'
        . '<h4 class="mb5 text-light" style="width: auto; float:left">Linked Documents</h4>'
        . '<div class="toolicon" style="width: auto; float:right" >
                <a data-target="#adddocument" href="'.$url.'" data-backdrop="static" data-toggle="modal" class="new-entity add toolsbutton">
                    <img src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/add_icon.png">
                </a>
            </div>'
        . '</div>';
}
echo $policyadd;
echo $uploadform;
if(isset($current_step)) {
    ?>
    <p></p>
    <a href="<?= Url::link('/admin/policies/issuepolicy/' . $policy_id); ?>" class="btn btn-success pull-right">Proceed
        to policy approval and issuance ></a>
    <?php
}
echo '</div>';
?>
<div class="col-xs-12" id="embedded_quote"></div>
<?php
echo '</div>
    </div>
    </div>';
echo Overlays::confirm();
?>
<script>
    var SITE_PATH = "<?= SITE_PATH ?>";
    var PRELOADER1 = '<?= HTML::AddPreloader()?>';
    var PRELOADER2 = '<?= HTML::AddPreloader('left', '40px', '40px')?>';
</script>
<script src="<?=RELATIVE_PROJECT_PATH.'/admin/policies/assets/js/policy.js'; ?>"></script>
<script src="<?=RELATIVE_PROJECT_PATH.'/admin/policies/assets/js/quotes-common.js'; ?>"></script>

