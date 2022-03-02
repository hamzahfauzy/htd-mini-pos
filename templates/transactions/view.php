<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Detail Penjualan : <?=$transaction->inv_code?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data penjualan</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="index.php?r=transactions/index" class="btn btn-warning btn-round">Kembali</a>
                        <?php if($transaction->status != 'finish'): ?>
                        <button class="btn btn-success btn-round" onclick="showSidebar()">Bayar</button>
                        <?php endif ?>
                        <button class="btn btn-success btn-round" onclick="cetak()">Cetak Struk</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">

                                <table class="table responsive nowrap">
                                    <tr>
                                        <td>Catatan</td>
                                        <td width="10px">:</td>
                                        <td><?=$transaction->notes?></td>
                                    </tr>
                                    <tr>
                                        <td>Invoice</td>
                                        <td width="10px">:</td>
                                        <td><?=$transaction->inv_code?></td>
                                    </tr>
                                    <tr>
                                        <td>Customer</td>
                                        <td>:</td>
                                        <td><?=$transaction->customer?$transaction->customer->name:'-'?></td>
                                    </tr>
                                    <tr>
                                        <td>Kasir</td>
                                        <td>:</td>
                                        <td><?=$transaction->user->name?></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>:</td>
                                        <td><span class="badge badge-<?=$badge[$transaction->status == 'on going' ? 'order' : 'pay']?>"><?=$transaction->status?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td>:</td>
                                        <td><?=number_format($transaction->total)?></td>
                                    </tr>
                                </table>
                            </div>
                            <?php if($success_msg): ?>
                                <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>
                            <div class="table-responsive table-hover table-sales">
                                <?php if(app('pos_mode') == 'Mode 2' && $transaction->status == 'on going'): ?>
                                <a href="index.php?r=pos/index&transaction_id=<?=$transaction->id?>" class="btn btn-primary">Tambah Data</a>
                                <?php endif ?>
                                <table class="table responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <th>Produk</th>
                                            <th>Status</th>
                                            <th>Sub Total</th>
                                            <th class="text-right"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($transaction->items as $index => $item): ?>
                                        <tr>
                                            <td>
                                                <?=$index+1?>
                                            </td>
                                            <td style="white-space:nowrap;">
                                                <?=$item->product->name?>
                                                <br>
                                                <small><?=$item->qty?> x <?=number_format($item->price)?></small>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?=$badge[$item->status]?>"><?= $item->status ?></span>
                                            </td>
                                            <td style="white-space:nowrap;"><?=number_format($item->subtotal)?></td>
                                            <td>
                                                <?php if($item->status == 'order'): ?>
                                                <a href="index.php?r=transactions/delete-item&id=<?=$item->id?>" class="btn btn-sm btn-danger"><i class="fas fa-trash fa-fw"></i> Hapus</a>    
                                                <!-- <a href="index.php?r=transactions/pay&id=<?=$item->id?>" class="btn btn-sm btn-success"><i class="fas fa-money-bill fa-fw"></i> Bayar</a>     -->
                                                <?php elseif($item->status == 'pay' && app('pos_mode') == 'Mode 1'): ?>
                                                <a href="index.php?r=transactions/retur&id=<?=$item->id?>" class="btn btn-sm btn-danger"><i class="fas fa-trash fa-fw"></i> Retur Barang</a>
                                                <?php else: ?>
                                                    -
                                                <?php endif ?>
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
            <?php if($transaction->status != 'finish'): load_templates('transactions/sidebar',compact('transaction')); endif; ?>
        </div>
    </div>
    <?php $transaction->user = [] ?>
    <script>
    async function cetak()
    {
        if(typeof(Android) === "undefined") 
        {
            window.location = 'index.php?r=print/invoice&inv_code=<?=$transaction->inv_code?>'
        }
        else
        {
            var transaction = <?=json_encode($transaction)?>;
            cetakAndroid(transaction)
        }
    }

    function showSidebar()
    {
        document.querySelector('.fly-transactions').classList.add('show')
        document.querySelector('.overlay-transactions').classList.add('show')
    }
    </script>
<?php load_templates('layouts/bottom') ?>