<div class="overlay-transactions" :class="{show:ringkasan_panel}" @click="ringkasan_panel = !ringkasan_panel"></div>
<div class="fly-transactions" :class="{show:ringkasan_panel}">
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <h2>Total : Rp. {{transactions.total ? transactions.total_format : 0}}</h2>
                <div class="transactions" style="height: calc(100vh - 400px);overflow: auto;">
                    <template v-for="(cat, index) in transactions" :key="index">
                        <h3 class="text-left text-bold">{{cat.name}}</h3>
                        <div class="item d-flex w-100 justify-content-between mb-3" v-for="(transaction, i) in cat.items" :key="i">
                            <div class="item-detail text-left">
                                <b>{{transaction.name}}</b>
                                <div class="text-left">
                                    <button class="btn btn-icon btn-danger btn-round btn-xs" @click="deleteTransaction(transaction.id, index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    &nbsp;
                                    <button class="btn btn-icon btn-danger btn-round btn-xs" @click="updateQty(transaction.id,transaction.qty, index,'minus')">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <input class="ml-3 mr-3" type="number" v-model="transaction.qty" style="width:50px;padding:5px" @change="updateQty(transaction.id,transaction.qty, index,'fixed')">
                                    <button class="btn btn-icon btn-primary btn-round btn-xs" @click="updateQty(transaction.id,transaction.qty, index,'plus')">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="item-subtotal">
                                Rp. {{transaction.subtotal_format}}
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div>
                <div v-if="transaction_id == 0">
                    <label for="">Kembalian : {{numberFormat(kembalian)}}</label>
                    <input type="number" class="form-control mb-2" name="payment_total" placeholder="Nominal Bayar" @keyup="hitungKembalian()" v-model="bayar">
                    <select class="form-control mb-2" v-model="customer">
                        <option value="">Pilih Kustomer</option>
                        <option v-for="customer in customers" :key="customer.id" :value="customer.code">{{customer.name}}</option>
                    </select>
                    <input type="text" class="form-control mb-2" placeholder="Catatan" v-model="notes">
                </div>
                <div class="d-flex">
                    <button id="btn-order" class="flex-fill btn btn-success" @click="doSubmit('order')">ORDER</button>
                </div>
            </div>
        </div>
    </div>
</div>