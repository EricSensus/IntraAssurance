<?php
// don't forget about this for custom templates, or errors will not show for server-side validation
// $zf_error is automatically created by the library and it holds messages about SPAM or CSRF errors
// $error is the name of the variable used with the set_rule method
$policyview = (isset($zf_error) ? $zf_error : (isset($error) ? $error : ''));

$policyview .= '<div class="row">'
                . '<div class="col-xs-12">'
                . '<table width="100%" border="0" cellpadding="10" class="policy table-striped">
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
                                <h4><strong>Quote Status:</strong> '.$quotestatus.'</h4>
                            </td>
                            <td align="right" class="premium">
                                <h4><strong>Premium Amount:</strong> '.$premium.'</h4>
                            </td>
                        </tr>
                      </tr>
                      </table>
                      <table width="100%" class="policy table-striped">
                      <tr>
                          <td colspan="2" class="heading">'
                            . '<h2 class="mb5 text-light">Validity</h2>'
                        . '</td>
                      </tr>
                      <tr>'
                        . '<td class="validity">'
                            . '<h4 class="startdate"><strong>*Start Date:</strong></h4>'.$startdate
                        . '</td>'
                        . '<td class="validity">'
                            . '<h4 class="enddate"><strong>*End Date:</strong> </h4>'.$enddate
                        . '</td>'
                    . '</tr>
                    </table>
                    <table width="100%" class="policy">
                        <tr>
                            <td class="heading">
                                <h2 class="mb5 text-light">Coverage</h2>
                            </td>
                        </tr>
                    </table>
                    <div class="coverage">'
                    . $coverage
                . '</div>'
                . '</div>'
                . '</div>';

$policyview .= '<div class="row last">'. $btnsubmit.'</div>';

if(is_null($display))
    echo $policyview;
