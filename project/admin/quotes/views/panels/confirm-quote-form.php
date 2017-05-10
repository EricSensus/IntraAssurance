<div class="panel-body">
    <p>Please confirm the above proposal on behalf of customer</p>
    <address>
        <p><strong>Name:</strong><?= $name ?></p>
        <p><strong>Product:</strong><?= $product['name'] ?></p>
    </address>
    <div class="col-md-12">
        <div class="pull-left">
            <button type="button" class="btn btn-success" id="accept">Confirm Quotation</button>
        </div>
        <!--        <div class="pull-right">-->
        <!--            <button type="button" class="btn btn-danger" data-toggle="modal"-->
        <!--                    data-target="#rejectModal">-->
        <!--                Reject-->
        <!--                Quotation-->
        <!--            </button>-->
        <!--        </div>-->
        <!--    </div>-->
        <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content panel-danger">
                    <div class="modal-header panel-heading">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title">Reject Quotation?</h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to reject this quotation?<br/>
                        Meanwhile, if you are absolutely sure. Please tell us why:<br/>
                        <form>
                            <div class="checkbox">
                                <label><input type="checkbox" value="expensive">The quotation is
                                    expensive</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" value="error">There is an error in this
                                    quotation</label>
                            </div>
                            <div class="checkbox disabled">
                                <label><input type="checkbox" value="other" disabled>Other/Personal
                                    reasons</label>
                            </div>
                        </form>
                        <blockquote>
                            We will keep this quote for 1 month, you can still come back and accept it
                        </blockquote>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">No, Wait
                        </button>
                        <button type="button" class="btn btn-danger" id="reject">Yes, Reject this Quote!
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <script>
            $(function () {
                var SITE_URL = '<?= SITE_PATH ?>';
                var to_send = null;
                $('#accept').click(function () {
                    to_send = "Yes";
                    sendResponse();
                });
                $('#reject').click(function () {
                    to_send = "No";
                    sendResponse();
                });
                function sendResponse() {
                    var newForm = $('<form>', {
                        'action': SITE_URL + '/quote/acceptreject?return=quotes',
                        'method': 'post'
                    }).append($('<input>', {
                        'name': 'action',
                        'value': to_send,
                        'type': 'hidden'
                    })).append($('<input>', {
                        'name': 'quote',
                        'value': '<?= $quote->id ?>',
                        'type': 'hidden'
                    }));
                    $(document.body).append(newForm);
                    newForm.submit();
                }
            });
        </script>
    </div>