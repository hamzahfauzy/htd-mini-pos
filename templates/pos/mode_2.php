<?php load_templates('layouts/top') ?>
<link rel="stylesheet" href="css/mode2.css">
    <div class="content py-5">
        <div id="app" style="display:none">
            <div class="page-inner">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- <div class="d-flex">
                                    <input type="text" class="form-control" id="kode-kustomer" placeholder="Kode Kustomer" name="customer_code">&nbsp;
                                    <input type="text" class="form-control" placeholder="Nama Kustomer" readonly>
                                </div> -->
                                <div>
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
                                    <div class="col-6 col-sm-3 col-md-2" v-if="d.products.length" v-for="(product, j) in d.products" :key="j">
                                        <div class="card card-post card-round" @click="addToCashier(product, d)" style="cursor:pointer" :tabindex="j">
                                            <span class="badge badge-success position-absolute mt-1 ml-1">Rp. {{product.price}}</span>
                                            <img class="card-img-top" :src="product.pic" :alt="product.name" height="100" style="object-fit: scale-down;" :style="{filter: product.stock == 0 ? 'grayscale(100%)' : ''}">
                                            <div class="card-body p-1">
                                                <div class="info-post text-center">
                                                    <p class="username" v-html="product.name"></p>
                                                    Stok : {{product.stock}}
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
                </div>
                <div class="fab-right">
                    <button class="btn btn-primary btn-sm py-3 d-flex align-items-center" @click="ringkasan_panel = !ringkasan_panel">
                        <i class="fas fa-fw fa-angle-left" v-if="!ringkasan_panel"></i>
                        <i class="fas fa-fw fa-angle-right" v-else></i>
                        <span class="h1 m-0 text-bold">{{itemCount}}</span>
                    </button>
                </div>
                <?php load_templates('pos/sidebar') ?>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script>
    window.pos_sess_id = '<?=$pos_sess_id?>';
    window.transaction_id = '<?=$transaction_id?>';
    window.app = <?=json_encode(app())?>;
    </script>
    <script src="js/mode2.js"></script>
<?php load_templates('layouts/bottom') ?>