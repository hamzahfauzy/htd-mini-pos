<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Detail Faktur : <?=$invoice->code?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data faktur</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="index.php?r=invoices/index" class="btn btn-warning btn-round">Kembali</a>
                        <?php if($invoice->status != 'finish'): ?>
                        <button class="btn btn-success btn-round" onclick="showSidebar()">Bayar</button>
                        <?php endif ?>
                        <button class="btn btn-success btn-round" onclick="cetak()">Cetak Struk</button>

                        <?php if(in_array('properties', config('modules')) && $invoice->metadata->transaction_type != 'Cash'): ?>
                        <a href="index.php?r=properties/download&invoice=<?=$invoice->id?>" class="btn btn-primary btn-round">Download File Akad</a>
                        <?php endif ?>
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
                                        <td><?=$invoice->notes?></td>
                                    </tr>
                                    <tr>
                                        <td>Invoice</td>
                                        <td width="10px">:</td>
                                        <td><?=$invoice->code?></td>
                                    </tr>
                                    <tr>
                                        <td>Customer</td>
                                        <td>:</td>
                                        <td><?=$invoice->customer?$invoice->customer->name:'-'?></td>
                                    </tr>
                                    <tr>
                                        <td>Pembuat Faktur</td>
                                        <td>:</td>
                                        <td><?=$invoice->creator->name?></td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>:</td>
                                        <td><span class="badge badge-<?=$badge[$invoice->status == 'on going' ? 'order' : 'pay']?>"><?=$invoice->status?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td>:</td>
                                        <td><?=number_format($invoice->total)?></td>
                                    </tr>
                                    <tr>
                                        <td>Sisa</td>
                                        <td>:</td>
                                        <td><?=number_format($invoice->remaining_payment)?></td>
                                    </tr>
                                </table>
                            </div>
                            <?php if($success_msg): ?>
                                <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>

                            <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                                <li class="nav-item submenu">
                                    <a class="nav-link active show" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="false">Pembayaran</a>
                                </li>
                                <li class="nav-item submenu">
                                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="true">Items</a>
                                </li>
                            </ul>

                            <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                                <div class="tab-pane fade active show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <div class="table-responsive table-hover table-sales">
                                        <table class="table responsive nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="20px">#</th>
                                                    <th>Jumlah Pembayaran</th>
                                                    <th>Jumlah yang harus Dibayar</th>
                                                    <th>Kembalian</th>
                                                    <th>Catatan</th>
                                                    <th>Kasir</th>
                                                    <th>Tanggal</th>
                                                    <th class="text-right"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($invoice->transactions as $index => $transaction): ?>
                                                <tr>
                                                    <td>
                                                        <?=$index+1?>
                                                    </td>
                                                    <td>
                                                        Rp. <?=number_format($transaction->amount)?>
                                                    </td>
                                                    <td>
                                                        Rp. <?=number_format($transaction->amount_total)?>
                                                    </td>
                                                    
                                                    <td>
                                                        Rp. <?=number_format($transaction->amount_return)?>
                                                    </td>
                                                    <td>
                                                        <?=$transaction->notes ?>
                                                    </td>
                                                    <td>
                                                        <?=$transaction->cashier ?>
                                                    </td>
                                                    <td>
                                                        <?=$transaction->created_at?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-success">Cetak Kwitansi</button>
                                                    </td>
                                                </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <div class="table-responsive table-hover table-sales">
                                        <?php if(app('pos_mode') == 'Mode 2' && $invoice->status == 'on going'): ?>
                                        <a href="index.php?r=pos/index&transaction_id=<?=$invoice->id?>" class="btn btn-primary">Tambah Data</a>
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
                                                <?php foreach($invoice->items as $index => $item): ?>
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
                                                        <a href="index.php?r=invoices/delete-item&id=<?=$item->id?>" class="btn btn-sm btn-danger"><i class="fas fa-trash fa-fw"></i> Hapus</a>    
                                                        <!-- <a href="index.php?r=invoices/pay&id=<?=$item->id?>" class="btn btn-sm btn-success"><i class="fas fa-money-bill fa-fw"></i> Bayar</a>     -->
                                                        <?php /*
                                                        <?php elseif($item->status == 'pay' && app('pos_mode') == 'Mode 1'): ?>
                                                        <a href="index.php?r=invoices/retur&id=<?=$item->id?>" class="btn btn-sm btn-danger"><i class="fas fa-trash fa-fw"></i> Retur Barang</a>
                                                        */ ?>
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
                </div>
            </div>
            <?php if($invoice->status != 'finish'): load_templates('invoices/sidebar',compact('invoice')); endif; ?>
        </div>
    </div>
    <?php $invoice->user = [] ?>
    <script>
    window.app = <?=json_encode(app())?>;
    window.config = <?=json_encode(config())?>;
    async function cetak()
    {
        var transaction = <?=json_encode($invoice)?>;
        if(window.config.printer == 'rawbt')
        {
            cetakRawBt(transaction)
        }
        else if(typeof(Android) === "undefined") 
        {
            
            cetakAndroid(transaction, 1)
            window.open('index.php?r=print/invoice&code=<?=$invoice->code?>')
        }
        else
        {
            cetakAndroid(transaction)
        }
    }

    function showSidebar()
    {
        document.querySelector('.fly-transactions').classList.add('show')
        document.querySelector('.overlay-transactions').classList.add('show')
    }
    
    function hideSidebar()
    {
        document.querySelector('.fly-transactions').classList.remove('show')
        document.querySelector('.overlay-transactions').classList.remove('show')
    }
    </script>
<?php load_templates('layouts/bottom') ?>