<?php
use Jenga\App\Views\HTML;
use Jenga\App\Request\Url;
use Jenga\App\Views\Overlays;
use Jenga\App\Helpers\Help;

HTML::script('$(document).ready( function () {
                    $("#save").click(function(){
                        $("#quoteform").submit();
                    });
                });');

echo '<div class="row">'
. '<div class="col-md-12">
    <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="col-md-4">
                <h4 class="mb5 text-light">Edit New Insurance Quote</h4>
                <p class="small"><strong>Edit</strong> New Quote</p>
            </div>';

        if($status == 'new' || $status == 'pending'){
            
            echo '<div class="col-md-6 col-md-offset-2">
                    <div class="policy_buttons mark_button" style="margin-right: 30px;">
                        <table border="0" width="140" style="text-align: center">
                          <tbody>
                          <tr>
                            <td height="50" class="toolicon">
                                <a data-toggle="modal" data-target="#statusmodal" href="'.Url::base().'/ajax/admin/quotes/markstatus/'.$id.'">
                                    <img title="Save" src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/mark_icon.png">
                                </a>
                            </td>
                          </tr>
                          <tr>
                            <td height="25" class="toolname"><span>Mark Customer Response</span></td>
                          </tr>
                          </tbody>
                        </table>                
                    </div>
                    <div class="policy_buttons save_button">
                    <table border="0" width="80" style="text-align: center">
                      <tbody>
                      <tr>
                        <td height="50" class="toolicon">
                            <a id="save" href="#">
                                <img title="Save" src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/save_icon.png">
                            </a>
                        </td>
                      </tr>
                      <tr>
                        <td height="25" class="toolname"><span>Save Quote</span></td>
                      </tr>
                      </tbody>
                    </table>                
                </div>';
        }
        elseif($status == 'policy_pending'){
            
            echo '<div class="col-md-6 col-md-offset-2">
                    <div class="policy_buttons create_button" style="margin-right: 30px;">
                        <table border="0" width="140" style="text-align: center">
                          <tbody>
                          <tr>
                            <td height="50" class="toolicon">
                                <a href="'.Url::base().'/admin/policies/createpolicy/'.$offer.'">
                                    <img title="Create" src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/create_icon.png">
                                </a>
                            </td>
                          </tr>
                          <tr>
                            <td height="25" class="toolname"><span>Create Policy</span></td>
                          </tr>
                          </tbody>
                        </table>                
                    </div>';
        }
        else{
            echo '<div class="col-md-4 col-md-offset-4">';
        }
        
        echo '<div class="policy_buttons preview_button">
                    <table border="0" width="100" style="text-align: center">
                      <tbody>
                      <tr>
                        <td height="50" class="toolicon">
                            <a id="preview" target="_blank" href="'.Url::route('/quotes/previewquote/{id}/{view}',['id' => Help::encrypt($id),'view'=> Help::encrypt('internal')]).'">
                                <img title="Preview Quote" src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/preview_icon.png">
                            </a>
                        </td>
                      </tr>
                      <tr>
                        <td height="25" class="toolname"><span>Preview Quote</span></td>
                      </tr>
                      </tbody>
                    </table> 
                </div>
                <div class="policy_buttons pdf_button">
                    <table border="0" width="100" style="text-align: center">
                      <tbody>
                      <tr>
                        <td height="50" class="toolicon">
                            <a id="preview" target="_blank" href="'.Url::base().'/ajax/admin/quotes/pdfquote/'.$id.'">
                                <img title="PDF Quote" src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/pdf_icon.png">
                            </a>
                        </td>
                      </tr>
                      <tr>
                        <td height="25" class="toolname"><span>Create PDF</span></td>
                      </tr>
                      </tbody>
                    </table> 
                </div>
                <div class="policy_buttons email_button">
                    <table border="0" width="100" style="text-align: center">
                      <tbody>
                      <tr>
                        <td height="50" class="toolicon">
                            <a data-toggle="modal" data-target="#emailmodal" href="'.Url::base().'/ajax/admin/quotes/emailquote/'.$id.'">
                                <img title="Email Quote" src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/email_icon.png">
                            </a>
                        </td>
                      </tr>
                      <tr>
                        <td height="25" class="toolname"><span>Mail Quote</span></td>
                      </tr>
                      </tbody>
                    </table> 
                </div>
            </div>
        </div>
    </div>
</div>';

echo Overlays::Modal(['id'=>'statusmodal']);
echo Overlays::Modal(['id'=>'emailmodal']);

echo '<div class="tabs row-padding">
    <ul role="tablist" class="nav nav-pills" id="myTabs">
      <li class="active" role="presentation" >
        <a aria-expanded="false" aria-controls="home" data-toggle="tab" role="tab" href="#quote-details">Quote Details</a>
      </li>
      <li role="presentation">
        <a aria-controls="profile" data-toggle="tab" role="tab" href="#linked-documents" aria-expanded="true">Linked Documents ('.$doccount.')</a>
      </li>
    </ul>
    </div>';

echo '<div class="row show-grid">
      <div class="dash-head clearfix mt15 mb20 shadow">
      <div class="tab-content col-md-12">';

//quote details tab
echo '<div role="tabpanel" class="tab-pane active" id="quote-details">';
    echo $quoteedit;
echo '</div>';

//linked documents tab
echo '<div role="tabpanel" class="tab-pane" id="linked-documents">';
    echo '<div class="mini-panel">';

$url = Url::route('/admin/documents/upload/{element}/{action}/{id}/{folder}', 
                ['element' => 'quotes','action' => 'edit','id' => $id,'folder'=>'documents']);

echo '<div class="panel-heading">'
    . '<h4 class="mb5 text-light" style="width: auto; float:left">Linked Documents</h4>'
        . '<div class="toolicon" style="width: auto; float:right" >
                <a data-target="#adddocument" href="'.$url.'" data-backdrop="static" data-toggle="modal" class="new-entity add toolsbutton">
                    <img src="'.RELATIVE_PROJECT_PATH.'/templates/admin/images/icons/small/add_icon.png">
                </a>
            </div>'
    . '</div>';

echo $linkeddocs;
echo $uploadform;

echo  '</div>'
. '<div class="dataTables_wrapper panel-footer">'
. '<div id="myentities">'
. '</div>';
echo '</div>'
. '</div>';

echo Overlays::confirm();
