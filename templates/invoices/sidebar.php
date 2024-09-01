<link rel="stylesheet" href="css/mode2.css">
<div class="overlay-transactions" onclick="hideSidebar()"></div>
<div class="fly-transactions">
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <h2>Total : Rp. <?=number_format($invoice->remaining_payment)?></h2>
                <div class="transactions" style="height: calc(100vh - 380px);overflow: auto;">
                    <?php foreach($invoice->items as $key => $item): ?>
                    <?php if($key == 0 || ($item->product->category->id != $invoice->items[$key-1]->product->category->id)): ?>
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
                <label for="" id="kembalian">Kembalian : (0)</label>
                <input type="number" class="form-control mb-2" name="payment_total" placeholder="Nominal Bayar" onkeyup="hitungKembalian(this.value)">
                <input type="text" class="form-control mb-2" placeholder="Catatan" id="notes">
                <button id="btn-bayar" class="btn btn-primary btn-block" onclick="bayar()">BAYAR</button>
                <form action="index.php?r=invoices/mass-pay&invoice_id=<?=$invoice->id?>" method="post" class="d-none" name="formBayar">
                    <input type="hidden" name="return_total">
                    <input type="hidden" name="total">
                    <input type="hidden" name="notes">
                    <input type="hidden" name="payment_type" value="cash">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function hitungKembalian(nominal){
    var kembalian = 0
    if(nominal > 0)
        kembalian = nominal - <?=$invoice->remaining_payment?>

    calculateTimeout = setTimeout(() => {
        document.querySelector('[name=return_total]').value = kembalian
        document.querySelector('#kembalian').innerHTML = "Kembalian : ("+(new Intl.NumberFormat('en-US', {})).format(kembalian)+")"
    }, 200);
}

async function bayar(){
    var nominal_bayar = document.querySelector('input[name=payment_total]')
    var kembalian     = document.querySelector('input[name=return_total]')
    var notes = document.querySelector('#notes').value
    const nominalStatus = parseInt(nominal_bayar.value) == 0 || nominal_bayar.value == ''

    if(nominalStatus)
    {
        alert('Pembayaran Gagal! Tidak ada nominal pembayaran.')
        return
    }

    if(parseInt(kembalian.value) < 0)
    {
        // alert('Pembayaran Gagal! Nominal pembayaran lebih kecil dari total transaksi')
        // return
        var confirmation = confirm('Nominal pembayaran lebih kecil dari total. Lanjutkan pembayaran ?')
        if(!confirmation)
        {
            return
        }
    }

    document.querySelector('input[name=total]').value = nominal_bayar.value
    document.querySelector('input[name=notes]').value = notes
    formBayar.submit()
    
}
</script>