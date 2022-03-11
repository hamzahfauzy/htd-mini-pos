<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
                        <h5 class="text-white op-7 mb-2">Mini POS</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="index.php?r=pos/index" class="btn btn-white btn-border btn-round mr-2">Kasir</a>
                        <a href="index.php?r=transaction/index" class="btn btn-secondary btn-round">Penjualan</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row mt--2">
                <div class="col-md-8">
                    <div class="card full-height">
                        <div class="card-header">
                            <div class="card-title">10 Transaksi Terakhir</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-hover table-sales">
                                <table class="table responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <th>Catatan</th>
                                            <th>Invoice</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($transactions)): ?>
                                            <tr>
                                                <td colspan="6" class="text-center"><i>Tidak ada data</i></td>
                                            </tr>
                                        <?php endif ?>
                                        <?php foreach($transactions as $index => $data): ?>
                                        <tr>
                                            <td>
                                                <?=$index+1?>
                                            </td>
                                            <td>
                                                <?=$data->notes?>
                                            </td>
                                            <td style="white-space:nowrap;">
                                                <?=$data->inv_code?><br>
                                                <?= date("H:i - d F Y",strtotime($data->created_at)) ?>
                                            </td>
                                            <td>
                                                <?= number_format($data->total) ?>
                                            </td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-body">
                        <h1 class="fw-bold text-uppercase text-success op-8">Omset Bulan ini</h1>
                        <h1 class="fw-bold">Rp.<?=number_format($omset)?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>