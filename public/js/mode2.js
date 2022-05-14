Vue.createApp({
    data() {
        return {
            message: 'Memuat data...',
            data: [],
            transactions: [],
            keyword:'',
            customer:'',
            notes:'',
            kembalian:0,
            itemCount:0,
            ringkasan_panel:false,
            bayar:0,
            pos_sess_id:window.pos_sess_id,
            transaction_id:window.transaction_id,
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
        async addToCashier(product, category){
            if(product.stock == 0) return
            var code = product.code
            var req = await fetch('index.php?r=api/transactions/add-to-cashier&code='+code+'&pos_sess_id='+this.pos_sess_id+'&category_id='+category.id)
            var res = await req.json()

            this.transactions = res
        },
        async updateQty(id, value, cat_id, type){
            var val = type == 'plus' ? parseInt(value)+1 : (type == 'minus' ? parseInt(value)-1 : value)
            if(val == 0)
            {
                this.deleteTransaction(id)
                return
            }
            var req = await fetch('index.php?r=api/transactions/update-qty&id='+id+'&pos_sess_id='+this.pos_sess_id+'&category_id='+cat_id+'&qty='+val)
            var res = await req.json()
            this.transactions = res
        },
        async deleteTransaction(id, cat_id){
            var req = await fetch('index.php?r=api/transactions/delete-transaction&id='+id+'&pos_sess_id='+this.pos_sess_id+'&category_id='+cat_id)
            var res = await req.json()
            this.transactions = res
        },
        hitungKembalian(){
            if(this.bayar > 0)
                this.kembalian = this.bayar - this.transactions.total
        },
        async doSubmit(status = 'bayar')
        {
            var item_exists = false
            for(cat in this.transactions)
            {
                if(this.transactions[cat].name == undefined) continue;
                var items = this.transactions[cat].items
                if(Object.keys(items).length)
                {
                    item_exists = true
                }
            }
            if(!this.transactions.total && !item_exists)
            {
                alert('Gagal! Tidak ada transaksi')
                return
            }

            if(this.notes == '' && this.transaction_id == 0)
            {
                alert('Catatan tidak boleh kosong.')
                return
            }

            if(status == 'bayar')
            {
                if(this.bayar == 0)
                {
                    alert('Pembayaran Gagal! Tidak ada nominal pembayaran.')
                    return
                }

                if(isNaN(this.kembalian))
                {
                    alert('Pembayaran Gagal!')
                    return
                }

                if(this.kembalian < 0)
                {
                    alert('Pembayaran Gagal! Nominal pembayaran lebih kecil dari total transaksi')
                    return
                }
            }

            var formData = new FormData
            formData.append('customer_code', this.customer)
            formData.append('paytotal', this.bayar)
            formData.append('pos_sess_id', this.pos_sess_id)
            formData.append('transaction_id', this.transaction_id)
            formData.append('notes', this.notes)
            
            var request = await fetch('index.php?r=api/transactions/bayar&status='+status,{
                'method':'POST',
                'body':formData
            })
            var response = await request.json()
            if(response.status == 'success') 
            {
                var transaction = response.transaction;
                if(typeof(Android) === "undefined") 
                {
                    if(status == 'bayar')
                    {
                        alert('Pembayaran Berhasil! Klik Oke untuk mencetak struk')
                    }
                    else
                    {
                        alert('Order Berhasil! Klik Oke untuk mencetak struk')
                    }

                    await fetch('index.php?r=print/invoice&inv_code='+response.inv_code)
                    window.location = 'index.php?r=transactions/view&id='+transaction.id
                    return
                }
                else
                {
                    cetakAndroid(transaction)
                }

                setTimeout(function(){
                    window.location = 'index.php?r=transactions/view&id='+transaction.id
                },3000)
                
            }
        }
    },
    watch:{
        transactions: function(newVal, oldVal) {
            this.itemCount = 0
            if(this.transactions){
                // return this.transactions.items.length
                for(index in this.transactions)
                {
                    var cat = this.transactions[index]
                    for(i in cat.items)
                    {
                        // console.log(i)
                        var transaction = cat.items[i]
                        this.itemCount += parseInt(transaction.qty)
                    }
                }
            }
        }
    }
}).mount('#app')