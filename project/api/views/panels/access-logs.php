<div class="row">
    <div class="col-md-12">
        <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                <h4 class="mb5 text-light">API Access Logs: <?= $data->token->name ?></h4>
                <p class="small"><strong>View</strong> API logs</p>
            </div>
            <div class="right toolholder">
            </div>
        </div>
    </div>
</div>
<div class="row show-grid">
    <div class="col-md-12 panel">
        <div class="dash-head clearfix mt15 mb20">
            <?= $logs_table ?>
        </div>
        <div class="dataTables_wrapper panel-footer">
            <div id="quotes_footer"></div>
        </div>
    </div>
</div>