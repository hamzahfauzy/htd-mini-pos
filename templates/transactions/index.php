<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Transaksi</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data transaksi</h5>
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
                            <div class="table-responsive table-hover table-sales">
                                <form action="">
                                <input type="hidden" name="r" value="transactions/index">
                                <div class="form-group">
                                    <label for="">Filter</label>
                                    <div class="d-flex">
                                        <input type="date" name="from" class="form-control" value="<?=isset($_GET['from']) ? $_GET['from'] : date('Y-m-01')?>">
                                        &nbsp;
                                        <input type="date" name="to" class="form-control" value="<?=isset($_GET['to']) ? $_GET['to'] : date('Y-m-t')?>">
                                        &nbsp;
                                        <button name="tampil" class="btn btn-success">Tampilkan</button>
                                        &nbsp;
                                        <button name="cetak" class="btn btn-primary">Cetak</button>
                                    </div>
                                </div>
                                </form>
                                <table class="table responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <th>Invoice</th>
                                            <th>Kustomer</th>
                                            <th>Kasir</th>
                                            <th>Jumlah Pembayaran</th>
                                            <th>Jumlah yang harus Dibayar</th>
                                            <th>Kembalian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($transactions)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center"><i>Tidak ada data</i></td>
                                            </tr>
                                        <?php endif ?>
                                        <?php foreach($transactions as $index => $data): ?>
                                        <tr>
                                            <td>
                                                <?=$index+1?>
                                            </td>
                                            <td style="white-space:nowrap;">
                                                <a href="index.php?r=invoices/view&id=<?=$data->invoice_id?>"><?=$data->code?></a><br>
                                                <i><?=$data->created_at?></i>
                                            </td>
                                            <td>
                                                <?= $data->customer_name ?? '-' ?>
                                            </td>
                                            <td style="white-space:nowrap;">
                                                <?= $data->cashier ?>
                                            </td>
                                            <td>
                                                Rp. <?= number_format($data->amount) ?>
                                            </td>
                                            <td>
                                            Rp. <?= number_format($data->amount_total) ?>
                                            </td>
                                            <td>
                                            Rp. <?= number_format($data->amount_return) ?>
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