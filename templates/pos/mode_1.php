<?php load_templates('layouts/top') ?>
<?php load_templates('pos/modal') ?>
    <div class="content py-5">
        <div class="page-inner">
            <div class="row row-card-no-pd">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-qrcode"></i></button>&nbsp;
                                <!--<button class="btn btn-success">Cari Produk</button>&nbsp;-->
                                <input type="text" class="form-control" id="kode-kustomer" placeholder="Kode Kustomer" name="customer_code">&nbsp;
                                <input type="text" class="form-control" placeholder="Nama Kustomer" readonly>
                            </div>
                            <p></p>
                            <p></p>
                            <div class="table-responsive">
                                <table class="table table-hover" id="transactions-table">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th width="100px">Qty</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total</td>
                                            <td>Rp. <span id="total">0</span></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-7">
                                <img src="" alt="" id="product-pic" width="100%">
                                </div>
                                <div class="col-12 col-md-5 text-right">
                                    <input type="number" class="form-control mb-2" name="payment_total" placeholder="Nominal Bayar" onkeyup="hitungKembalian(this.value, event)" value="0">
                                    <input type="number" class="form-control mb-2" name="return_total" placeholder="Kembalian" value="0" readonly>
                                    <button id="btn-bayar" class="btn btn-primary btn-block" onclick="doSubmit(true)">BAYAR</button>
                                    <?php /*
                                    <button id="btn-bayar" class="btn btn-success btn-block" onclick="doSubmit()">ORDER</button>
                                    */ ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    var codeTimeout = null;
    var calculateTimeout = null;
    window.transactions = []

    document.querySelector('.wrapper').classList.add('sidebar_minimize')
    // document.querySelector('#input-kode').focus()

    function addToCashier(ev, kode)
    {

        // in case if barcode scanner has enter button
        // if(ev.key.toLowerCase() == 'enter')
        // {
            
        // }

        fetchToCashier(kode, ev.target)
    }

    function fetchToCashier(code, target)
    {
        if(code == '') return
        if(codeTimeout) clearTimeout(codeTimeout)

        codeTimeout = setTimeout(() => {
            fetch('index.php?r=api/invoices/add-to-cashier&code='+code+'&pos_sess_id=<?=$pos_sess_id?>')
            .then(res => res.json())
            .then(res => {
                if(!res.hasOwnProperty('error'))
                {
                    window.transactions = res
                    initTransactionToTable()
                    document.querySelector('#product-pic').src = res.pic
                    setTimeout(() => {
                        document.querySelector('#product-pic').src = ''
                    }, 2000);
                }
                target.value = ""
            })
            .catch(err => {
                console.log(err)
            })
        }, 1000);
    }

    function updateQty(el, id)
    {
        fetch('index.php?r=api/invoices/update-qty&id='+id+'&pos_sess_id=<?=$pos_sess_id?>&qty='+el.value)
        .then(res => res.json())
        .then(res => {
            window.transactions = res
            initTransactionToTable()
        })
    }

    function deleteTransaction(id)
    {
        fetch('index.php?r=api/invoices/delete-transaction&id='+id+'&pos_sess_id=<?=$pos_sess_id?>')
        .then(res => res.json())
        .then(res => {
            window.transactions = res
            initTransactionToTable()
        })
    }

    function initTransactionToTable()
    {
        // console.log(window.transactions)
        // var table = document.querySelector('#transactions-table')
        var xTable = document.getElementById('transactions-table');
        xTable.getElementsByTagName("tbody")[0].innerHTML = "";
        var index = 0;
        xTable.getElementsByTagName("tbody")[0].innerHTML += `<tr><td style="padding:0!important;" colspan="6">
                                            <input type="text" class="form-control" id="input-kode" placeholder="Ketikan kode produk disini..." onkeyup="addToCashier(event, this.value)" style="border-radius:0;border:0;" autofocus>
                                        </td></tr>`;

        for(tr in window.transactions.items)
        {
            var transaction = window.transactions.items[tr]
            
            xTable.getElementsByTagName("tbody")[0].innerHTML += `
                        <tr id="data-${index}" class="data-row" data-id="${transaction.id}">
                            <td>${transaction.code}</td>
                            <td>${transaction.name}</td>
                            <td>${transaction.price_format}</td>
                            <td><input type="number" id="q-${index}" value="${transaction.qty}" min="1" class="p-1" style="width:100%" onchange="updateQty(this,${transaction.id})"></td>
                            <td>${transaction.subtotal_format}</td>
                            <td><button class="btn btn-danger btn-sm" onclick="deleteTransaction(${transaction.id})"><i class="fas fa-times"></i></button></td>
                        </tr>`
            index++
        }
       

        document.querySelector('span#total').innerHTML = window.transactions.hasOwnProperty('total') ? window.transactions.total_format : 0

        setTimeout(() => {
            document.querySelector('#input-kode').focus()
        }, 500);
    }

    function hitungKembalian(nominal,event){
        if(calculateTimeout) clearTimeout(calculateTimeout)

        var kembalian = 0
        if(nominal > 0)
            kembalian = nominal - window.transactions.total


        calculateTimeout = setTimeout(() => {
            document.querySelector('input[name=return_total]').value = kembalian
        }, 200);

        var key = event.key
		if(key == 'Enter')
		{
			document.querySelector('#btn-bayar').click()
		}
    }
    
    async function doSubmit(bayar = false){
        var nominal_bayar = document.querySelector('input[name=payment_total]')
        var kembalian     = document.querySelector('input[name=return_total]')
        if(bayar && nominal_bayar.value == 0)
        {
            alert('Pembayaran Gagal! Tidak ada nominal pembayaran.')
            return
        }

        if(bayar && kembalian.value < 0)
        {
            alert('Pembayaran Gagal! Nominal pembayaran lebih kecil dari total transaksi')
            return
        }

        var formData = new FormData
        formData.append('customer_code', document.querySelector('#kode-kustomer').value)
        formData.append('paytotal', nominal_bayar.value)
        formData.append('payment_type', 'cash')
        formData.append('pos_sess_id', '<?=$pos_sess_id?>')
        
        var request = await fetch('index.php?r=api/invoices/bayar' + (bayar ? '&status=bayar' : ''),{
            'method':'POST',
            'body':formData
        })
        var response = await request.json()
        if(response.status == 'success') 
        {
            var invoice = response.invoice;
            
            if(typeof(Android) === "undefined") 
            {
                if(bayar)
                {
                    alert('Pembayaran Berhasil! Klik Oke untuk mencetak struk')
                }
                else
                {
                    alert('Order Berhasil! Klik Oke untuk mencetak struk')
                }
                var res = await fetch('index.php?r=print/invoice&inv_code='+response.inv_code)
            }
            else
            {
                var formatter = new Intl.NumberFormat('en-US', {});

                var invoiceItems = "[C]--------------------------------\n";
                invoice.items.forEach(item=>{
                    invoiceItems += `[L]${item.product.shortname}\n`
                    invoiceItems += `[L]${item.qty} x ${formatter.format(item.price)} [R]${formatter.format(item.subtotal)}\n`
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

            setTimeout(function(){
                window.location = 'index.php?r=invoices/view&id='+transaction.id
            },3000)
            
        }
        // .then(res => res.json())
        // .then(res => {
        //     location.reload()
        // })

        
    }

    initTransactionToTable()
    </script>
    <!-- include the library -->
<script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
<script>
var html5QrcodeScanner = new Html5QrcodeScanner(
	"qr-reader", { fps: 24, qrbox: 250 });

function onScanSuccess(decodedText, decodedResult) {
    // console.log(`Code scanned = ${decodedText}`, decodedResult);
    var audio = new Audio('sounds/success.wav');
    audio.play();
    fetchToCashier(decodedText, document.querySelector('#input-kode'))
    // document.querySelector('#input-kode').value = decodedText
}

html5QrcodeScanner.render(onScanSuccess);
</script>
<?php load_templates('layouts/bottom') ?>
