<div class="row">
    <?php if(!empty($debtorsData) || !empty($creditorsData)) : ?>
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-6">
                        <?php  if(!empty($debtorsData['total'])) : ?>
                            <?= $this->render('//business/charts/debtors', ['debtorsData' => $debtorsData]) ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <?php  if(!empty($creditorsData['total'])) : ?>
                            <?= $this->render('//business/charts/creditors', ['creditorsData' => $creditorsData]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php  if( !empty($customersData['total'])) : ?>
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-sm-6">

                        <?= $this->render('//business/charts/total-per-customer', ['customersData' => $customersData]) ?>
                        <span class="col-sm-offset-4 label label-info text-right">Grand total: R<?=
                            number_format($customersData['total']['grand'], 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if(!empty($customersBarChartData)) : ?>
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?= $this->render('//business/charts/barchart-aggregated', [
                        'bars' => $customersBarChartData
                    ]) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>