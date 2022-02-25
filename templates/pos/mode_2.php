<?php load_templates('layouts/top') ?>
<?php load_templates('pos/modal') ?>
    <div class="content py-5">
        <div id="app">
            <div class="page-inner">
                <div class="row">
                    <div class="col-12 col-md-7">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><i class="fas fa-qrcode"></i></button>&nbsp;
                                    <input type="text" class="form-control" id="kode-kustomer" placeholder="Kode Kustomer" name="customer_code">&nbsp;
                                    <input type="text" class="form-control" placeholder="Nama Kustomer" readonly>
                                </div>
                                <div class="mt-2">
                                <input type="text" class="form-control" placeholder="Cari Kode Produk / Nama Produk" name="customer_code" @keyup="search" v-model="keyword">
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="col-12" :class="{'d-none':isLoad}" v-html="message"></div>
                                <div class="row" v-for="(d,i) in data" :key="i">
                                    <div class="col-12">
                                        <h4 v-html="d.name"></h4>
                                    </div>
                                    <div class="col-sm-12 col-md-3" v-if="d.products.length" v-for="(product, j) in d.products" :key="j">
                                        <div class="card card-post card-round" @click="addToCashier(product.code)" style="cursor:pointer">
                                            <span class="badge badge-success position-absolute mt-1 ml-1">Rp. {{product.price}}</span>
                                            <img class="card-img-top" :src="product.pic" :alt="product.name" height="100" style="object-fit: scale-down;">
                                            <div class="card-body p-1">
                                                <div class="info-post">
                                                    <p class="username text-center" v-html="product.shortname"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12" v-else>
                                        <i>Tidak ada produk!</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <h2>Ringkasan</h2>
                                    <div class="transactions">
                                        <div class="item d-flex w-100 justify-content-between mb-3" v-for="(transaction, i) in transactions.items" :key="i">
                                            <div class="item-detail text-left">
                                                <b>{{transaction.name}}</b>
                                                <div class="text-left">
                                                    <button class="btn btn-icon btn-primary btn-round btn-xs" @click="updateQty(transaction.id,transaction.qty,'plus')">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                    <span class="ml-3 mr-3">{{transaction.qty}}</span>
                                                    <button class="btn btn-icon btn-danger btn-round btn-xs" @click="updateQty(transaction.id,transaction.qty,'minus')">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                    <button class="btn btn-icon btn-danger btn-round btn-xs" @click="deleteTransaction(transaction.id)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="item-subtotal">
                                                Rp. {{transaction.subtotal_format}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <input type="number" class="form-control mb-2" name="payment_total" placeholder="Nominal Bayar" @keyup="hitungKembalian()" v-model="bayar">
                                    <input type="number" class="form-control mb-2" name="return_total" placeholder="Kembalian" v-model="kembalian" readonly>
                                    <button id="btn-bayar" class="btn btn-primary btn-block" @click="bayar()">BAYAR</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
    <script>
    document.querySelector('.wrapper').classList.add('sidebar_minimize')
    
    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", { fps: 24, qrbox: 250 });

    function onScanSuccess(decodedText, decodedResult) {
        var audio = new Audio('sounds/success.wav');
        audio.play();
        // fetchToCashier(decodedText, document.querySelector('#input-kode'))
    }

    html5QrcodeScanner.render(onScanSuccess);
    </script>
    <script src="https://unpkg.com/vue@3"></script>
    <script>
    Vue.createApp({
        data() {
            return {
                message: 'Memuat data...',
                data: [],
                transactions: [],
                keyword:'',
                customer:'',
                kembalian:0,
                bayar:0,
                pos_sess_id:'<?=$pos_sess_id?>',
                isLoad:false
            }
        },
        async created(){
            await this.initData()
        },
        methods:{
            async search(){
                await this.initData(this.keyword)
            },
            async initData(keyword = ''){
                this.isLoad = false
                var req = await fetch('index.php?r=api/products/get-all-by-categories&keyword='+keyword)
                var res = await req.json()
                this.data = res.data
                if(this.data.length)
                    this.isLoad = true
                else
                    this.message = '<i>Tidak ada data!</i>'
            },
            async addToCashier(code){
                var req = await fetch('index.php?r=api/transactions/add-to-cashier&code='+code+'&pos_sess_id='+this.pos_sess_id)
                var res = await req.json()

                this.transactions = res
            },
            async updateQty(id, value, type){
                var val = type == 'plus' ? parseInt(value)+1 : parseInt(value)-1
                if(val == 0)
                {
                    this.deleteTransaction(id)
                    return
                }
                var req = await fetch('index.php?r=api/transactions/update-qty&id='+id+'&pos_sess_id='+this.pos_sess_id+'&qty='+val)
                var res = await req.json()
                this.transactions = res
            },
            async deleteTransaction(id){
                var req = await fetch('index.php?r=api/transactions/delete-transaction&id='+id+'&pos_sess_id='+this.pos_sess_id)
                var res = await req.json()
                this.transactions = res
            },
            hitungKembalian(){
                if(this.bayar > 0)
                    this.kembalian = this.bayar - this.transactions.total
            },
            async bayar(){
                if(this.bayar == 0)
                {
                    alert('Pembayaran Gagal! Tidak ada nominal pembayaran.')
                    return
                }

                if(this.kembalian < 0)
                {
                    alert('Pembayaran Gagal! Nominal pembayaran lebih kecil dari total transaksi')
                    return
                }

                var formData = new FormData
                formData.append('customer_code', this.customer)
                formData.append('paytotal', this.bayar)
                formData.append('pos_sess_id', this.pos_sess_id)
                
                var request = await fetch('index.php?r=api/transactions/bayar',{
                    'method':'POST',
                    'body':formData
                })
                var response = await request.json()
                if(response.status == 'success') 
                {
                    if(typeof(Android) === "undefined") 
                    {
                        alert('Pembayaran Berhasil! Klik Oke untuk mencetak struk')
                        var res = await fetch('index.php?r=print/invoice&inv_code='+response.inv_code)
                    }
                    else
                    {
                        var formatter = new Intl.NumberFormat('en-US', {});
                        var transaction = response.transaction;

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

                    setTimeout(function(){
                        window.location = 'index.php?r=transactions/view&id='+transaction.id
                    },3000)
                    
                }
            }
        }
    }).mount('#app')
    </script>
<?php load_templates('layouts/bottom') ?>