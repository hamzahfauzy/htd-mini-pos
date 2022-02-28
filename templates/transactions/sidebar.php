<link rel="stylesheet" href="css/mode2.css">
<div class="fly-transactions">
    <div class="overlay-transactions"></div>
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <h2>Total : Rp. <?=number_format($transaction->total)?></h2>
                <div class="transactions" style="height: calc(100vh - 380px);overflow: auto;">
                    <?php foreach($transaction->items as $key => $item): ?>
                    <?php if($key == 0 || ($item->product->category->id != $transaction->items[$key-1]->product->category->id)): ?>
                    <h3 class="text-left"><?=$item->product->category->name?></h3>
                    <?php endif ?>
                    <div class="item d-flex w-100 justify-content-between mb-3">
                        <div class="item-detail text-left">
                            <b><?=$item->product->name?></b>
                            <div class="text-left">
                                <span><?=$item->qty?> X <?=number_format($item->price)?></span>
                            </div>
                        </div>
                        <div class="item-subtotal">
                            Rp. <?=number_format($item->subtotal)?>
                        </div>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="text-right">
                <input type="number" class="form-control mb-2" name="payment_total" placeholder="Nominal Bayar" onkeyup="hitungKembalian(this.value)">
                <input type="number" class="form-control mb-2" name="return_total" placeholder="Kembalian" readonly>
                <button id="btn-bayar" class="btn btn-primary btn-block" onclick="bayar()">BAYAR</button>
                <form action="index.php?r=transactions/mass-pay&transaction_id=<?=$transaction->id?>" method="post" class="d-none" name="formBayar">
                    <input type="hidden" name="total">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function hitungKembalian(nominal){
    var kembalian = 0
    if(nominal > 0)
        kembalian = nominal - <?=$transaction->total?>

    calculateTimeout = setTimeout(() => {
        document.querySelector('input[name=return_total]').value = kembalian
    }, 200);
}

async function bayar(){
    var nominal_bayar = document.querySelector('input[name=payment_total]')
    var kembalian     = document.querySelector('input[name=return_total]')
    if(nominal_bayar.value == 0)
    {
        alert('Pembayaran Gagal! Tidak ada nominal pembayaran.')
        return
    }

    if(kembalian.value < 0)
    {
        alert('Pembayaran Gagal! Nominal pembayaran lebih kecil dari total transaksi')
        return
    }

    document.querySelector('input[name=total]').value = nominal_bayar.value
    formBayar.submit()
    
}
</script>