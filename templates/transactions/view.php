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
                            <table class="table table-bordered">
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
                                    <td>Total</td>
                                    <td>:</td>
                                    <td><?=number_format($transaction->total)?></td>
                                </tr>
                            </table>
                            <?php if($success_msg): ?>
                                <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
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
                                            <td><?=$item->product->name?></td>
                                            <td><?=$item->qty?></td>
                                            <td><?=number_format($item->price)?></td>
                                            <td><?=number_format($item->subtotal)?></td>
                                            <td>
                                                <?php if($item->status == 'retur'): ?>
                                                <i>Barang dikembalikan</i>
                                                <?php else: ?>
                                                <a href="index.php?r=transactions/retur&id=<?=$item->id?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Retur Barang</a>
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
    <script>
    async function cetak()
    {
        if(typeof(Android) === "undefined") 
        {
            var res = await fetch('index.php?r=print/invoice&inv_code='+response.inv_code)
        }
        else
        {
            var formatter = new Intl.NumberFormat('en-US', {});
            var transaction = <?=json_encode($transaction)?>;

            var transactionItems = "[C]--------------------------------\n";
            transaction.items.forEach(item=>{
                transactionItems += `[L]${item.product.shortname}\n`
                transactionItems += `[L]${item.qty} x ${formatter.format(item.price)} [R]${formatter.format(item.subtotal)}\n`
            })
            transactionItems += "[C]--------------------------------\n";

            var printText = "[C]<b><?=app('name')?></b>\n" +
                            "[C]<?=app('address')?>\n" +
                            "[C]<?=app('phone')?>\n" +
                            "[C]--------------------------------\n" +
                            "[C]<?=date('d/m/Y H:i')?>\n" +
                            transactionItems +
                            `[L]<b>Total</b> [R]${formatter.format(transaction.total)}\n` +
                            "[C]--------------------------------\n" +
                            `[L]<b>Bayar</b> [R]${formatter.format(transaction.paytotal)}\n` +
                            "[C]--------------------------------\n" +
                            `[L]<b>Kembalian</b> [R]${formatter.format(transaction.return_total)}\n` +
                            "[C]--------------------------------\n\n" +
                            "[C]Terimakasih telah berbelanja di\n"+
                            "[C]<?=app('name')?>"
                            ;

            Android.printInvoice(printText);
        }
    }
    </script>
<?php load_templates('layouts/bottom') ?>