<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Penjualan</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data penjualan</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if($success_msg): ?>
                            <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table datatable responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <th>Catatan</th>
                                            <th>Invoice</th>
                                            <th>Kustomer</th>
                                            <th>Kasir</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th class="text-right"></th>
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
                                                <i><?=$data->created_at?></i>
                                            </td>
                                            <td>
                                                <?= $data->customer ? $customer->name : '-' ?>
                                            </td>
                                            <td style="white-space:nowrap;">
                                                <?= $data->user->name ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?=$badge[$data->status == 'on going' ? 'order' : 'pay']?>"><?=$data->status?></span>
                                            </td>
                                            <td>
                                                <?= number_format($data->total) ?>
                                            </td>
                                            <td>
                                                <a href="index.php?r=transactions/view&id=<?=$data->id?>" class="btn btn-sm btn-success"><i class="fas fa-eye"></i> Lihat</a>
                                            </td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>