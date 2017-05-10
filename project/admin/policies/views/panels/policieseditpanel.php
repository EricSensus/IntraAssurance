<?php
use Jenga\App\Request\Url;

echo '<div class="row">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                <h4 class="mb5 text-light">Edit Insurance Policy</h4>
                <p class="small"><strong>Edit</strong> Policy</p>
            </div>
            <div class="right">
                <a href="'.Url::link('/admin/policies').'" class="btn btn-default"><i class="fa fa-close"></i> Cancel</a>
                <button class="btn btn-primary" id="save_edits"><i class="fa fa-save"></i> Save Edits</button>
            </div>
            ';
echo '</div>
</div>
</div>';

?>
<!--Tabs-->
<div class="tabs row-padding">
    <ul role="tablist" class="nav nav-pills" id="myTabs">
        <li class="active" role="presentation">
            <a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" href="#quote-details">
                <i class="fa fa-eye"></i> Policy Overview</a>
        </li>
        <li role="presentation">
            <a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" href="#validity_and_issue">
                <i class="fa fa-calendar"></i> Validity and Issue</a>
        </li>
        <li role="presentation">
            <a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" href="#coverage">
                <i class="fa fa-product-hunt"></i> Coverage</a>
        </li>
        <li role="presentation">
            <a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" href="#policy_cover_details">
                <i class="fa fa-list"></i> Policy Cover Details</a>
        </li>
        <li role="presentation">
            <a aria-controls="profile" data-toggle="tab" role="tab" href="#linked-documents" aria-expanded="true">
                <i class="fa fa-files-o"></i> Linked Documents</a>
        </li>
    </ul>
</div>
<!--End Tabs-->

<!--Tab Content-->
    <div class="row show-grid">
        <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="tab-content col-xs-12">
                <div role="tabpanel" class="tab-pane active" id="quote-details">
                    <?php
                        echo '<table width="100%" border="0" cellpadding="10" class="policy table-striped">
                        <tr>
                            <td colspan="2" class="heading">
                                <h2 class="mb5 text-light">Policy Overview: '.$policyno.'</h2></td>
                        </tr>
                        <tr>
                            <td width="50%">
                                <p><strong>Quotation Reference Number:</strong> '.$quoteno.'</p>
                            </td>
                            <td width="50%" align="right">
                                <p><strong>Date Generated:</strong> '.$dategen.'</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong>Customer:</strong> '.$customername.'</p>
                            </td>
                            <td align="right">
                                <p><strong>Policy Number: </strong> '.$policyno.'</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong>Email Address:</strong> '.$policyemail.'</p>
                            </td>
                            <td align="right">
                                <p><strong>Product: </strong> '.$product.'</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong>Insurer: </strong> '.$insurer.'</p>
                            </td>
                            <td align="right">
                            </td>
                        </tr>
                        <tr>
                            <td align="left" class="premium">
                                <h4><strong>Status:</strong> '.$status.'</h4>
                            </td>
                            <td align="right" class="premium">
                                <h4><strong>Premium Amount:</strong> '.$premium.'</h4>
                            </td>
                        </tr>
                        </tr>
                    </table>';
                ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="validity_and_issue">
                    <?=$policyedit; ?>
                </div>

                <div role="tabpanel" class="tab-pane" id="coverage">
                    <table width="100%" class="policy">
                        <tr>
                            <td class="heading">
                                <h2 class="mb5 text-light">Coverage</h2>
                            </td>
                        </tr>
                    </table>
                    <div class="coverage"><?=$coverage; ?></div>
                </div>
                <div role="tabpanel" class="tab-pane" id="policy_cover_details">
                    <table width="100%" class="policy">
                        <tr>
                            <td class="heading">
                                <h2 class="mb5 text-light">Policy Cover Details</h2>
                            </td>
                        </tr>
                    </table>
                    <div class="coverage"><?=$policy_cover_details; ?></div>
                </div>

                <div role="tabpanel" class="tab-pane" id="linked-documents">
                    <div class="panel-heading">
                        <h4 class="mb5 text-light" style="width: auto; float:left">Linked Documents</h4>
                        <div class="toolicon" style="width: auto; float:right" >
                            <?php
                            $url = Url::route('/admin/documents/upload/{element}/{action}/{id}/{folder}',
                                ['element' => 'policies','action' => 'edit','id' => $policy_id,'folder'=>'documents']);
                            ?>
                            <a data-target="#adddocument" href="<?=$url; ?>" data-backdrop="static" data-toggle="modal" class="new-entity add toolsbutton">
                                <img src="<?=RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/add_icon.png'; ?>">
                            </a>
                        </div>
                    </div>
                    <?=$linked_docs; ?>
                </div>
            </div>
        </div>
    </div>
<!--End Tab Content-->

<?=$uploadform; ?>
<script>
    $('button#save_edits').on('click', function () {
        $('form#confirmform').submit();
    });
</script>