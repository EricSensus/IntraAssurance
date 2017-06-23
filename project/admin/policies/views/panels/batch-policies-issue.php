<?php
use Jenga\App\Request\Url;
use Jenga\App\Views\HTML;
?>
<script>
    $(document).ready(function () {
        $('input[type="submit"]').on('click',function(ev){
            ev.preventDefault(); //prevent default form submit

            var idattr = $(this).attr('id');
            var rowid = idattr.split('_')[1];

            //get the input
            var policyid = $('#policyid_'+rowid).val();
            var policyno = $('#policynumber_'+rowid).val();
            var issuedate = $('#issuedate_'+rowid).val();
            var sendemail = $('#sendemail_'+rowid).val();

            $('div.'+rowid).html('".HTML::AddPreloader()."');

            var data = {
                ajax: 'yes',
                policyid: policyid,
                policynumber: policyno,
                issuedate: issuedate,
                btnsubmit: 'btnsubmit'
            },
            url = '<?=Url::base(); ?>/admin/policies/saveissue',
            destination = '<?=Url::link('/admin/policies'); ?>';

            $.ajax({
                method: 'post',
                url: url,
                data : data,
                success: function(response){
                    $('#policyid_'+rowid).remove();
                    $('div.'+rowid).html(response);

                    if($('input[name*="policyid_"]').length == 0){
                        var url = $('input[name="destination"]').val();
                        window.location.href = destination;
                    }
                },
                error: function(response){
                    $('div.'+rowid).html(response);
                }
            });
        })
    });
</script>
<?php
echo '<table width="100%" class="policy table-striped">
      <tr>
          <td class="heading" colspan="2">'
            . '<h2 class="mb5 text-light">Batch Policy Issuance</h2>'
        . '</td>
      </tr>
      </table>';
//      print_r(get_defined_vars());exit;
for($r = 0; $r < $count; $r++){
    
    echo '<div class="'.$r.'">'
    . '<table width="100%" id="'.$r.'" border="0" cellpadding="10" class="policy table table-bordered table-striped">
      <tr>
        <td colspan="2" class="heading"><h4>Customer: '.$customer[$r].'</h4></td>
      </tr>
      <tr>
        <td>Policy Number: '.${'policynumber_'.$r}.'</td>
        <td>Issue Date'.${'issuedate_'.$r}.'</td>
      </tr>
      <tr>
        <td>
            <div class="cell">'.${'sendemail_'.$r.'_yes'}.'</div>'
          . '<div class="cell">'.$labels['label_'.$r].'</div>
      </td>
        <td><div style="float:right; margin-right: 50px;">'.${'btnsubmit_'.$r}.'</div></td>
      </tr>
    </table>
    </div>';
}
