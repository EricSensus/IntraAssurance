<?php
use Jenga\App\Request\Url;

echo '<div class="row">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                    <h4 class="mb5 text-light">Match Import Columns</h4>
                    <p class="small"><strong>Align</strong> the imported document columns with the Policies columns</p>
            </div>
            <div class="right toolholder">
            </div>
    </div>
</div>
</div>';

echo $alert;
echo '<form action="'.Url::route('/admin/{element}/{action}', ['element'=>'policies','action'=>'integrateimport']).'" method="post">'
        . '<input type="hidden" name="filepath" value="'.$filepath.'">'
        . '<div class="row show-grid">'
        . '<div class="col-md-12 panel">
            <div class="dash-head clearfix mt15 mb20">
            '.$policies_match_columns.'
            </div>';

echo '<div class="dataTables_wrapper panel-footer" style="padding-bottom:15px">'
. '<div id="policies_footer" style="padding-top:10px;" >'
        . '<div class="col-md-3 pull-right">'
        . '<button type="submit" class="btn btn-default pull-right">Finish Policies Records Import</button>'
        . '</div>'
. '</div>'
. '</div>';

echo '</div>'
    . '</div>'
. '</form>';