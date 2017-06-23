<div class="row padding-bottom-20px">
    <div class="col-md-12">
        <div class="dash-head clearfix mt15 mb20 shadow">
            <div class="left">
                <h4 class="mb5 text-light">Reporting Overview & Statistics</h4>
                <p class="small"><strong>Insurance</strong> reporting ovreview</p>
            </div>
            <div class="right mt10">
            </div>
        </div>
    </div>
</div>

<div class="row show-grid padding-bottom-20px">
    <div class="col-md-6">
        <div class="shadow">
            <div class="mini-panel">
                <?php
                    $this->loadPanel('products-share',['type'=>'pie','id'=>'share-pie','width'=>'100%','height'=>'380px']);
                ?>
            </div>
            <div class="panel-footer">
                <p><strong>Products</strong> Percentage Share</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="shadow">
            <div class="mini-panel">
                <?php
                    $this->loadPanel('monthly-quotes',['type'=>'column','id'=>'monthly-quotes','width'=>'100%','height'=>'380px']);
                ?>
            </div>
            <div class="panel-footer">
                <p><strong>Sales Conversion By Month</strong> Quotes Generation</p>
            </div>
        </div>
    </div>
</div>

<div class="row show-grid padding-bottom-20px">
    <div class="col-md-6">
        <div class="shadow">
            <div class="mini-panel">
                <?php
                    $this->loadPanel('monthly-policies',['type'=>'stackedcolumn','id'=>'monthly-policies','width'=>'100%','height'=>'380px']);
                ?>
            </div>
            <div class="panel-footer"><p><strong>Monthly</strong> Policies Generated</p></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="shadow">
            <div class="mini-panel">
                <?php
                    $this->loadPanel('agents-share',['type'=>'pie','id'=>'agent-share-pie','width'=>'100%','height'=>'380px']);
                ?>
            </div>
            <div class="panel-footer">
                <p><strong>Monthly</strong> Agent Performance</p>
            </div>
        </div>
    </div>
</div>

<!--Sales conversions (current month)-->
<div class="row show-grid padding-bottom-20px">
    <div class="col-md-6">
        <div class="shadow">
            <div class="mini-panel">
                <?php
                    $this->loadPanel('current-scs',['type'=>'stackedcolumn','id'=>'current-month-sc','width'=>'100%','height'=>'380px']);
                ?>
            </div>
            <div class="panel-footer"><p><strong>Sales Conversions (current month)</strong> these are quotes which were converted into policies.</p></div>
        </div>
    </div>
</div>