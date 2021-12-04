<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Detail Produk : <?=$data->name?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data produk</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="index.php?r=products/edit&id=<?=$data->id?>" class="btn btn-secondary btn-round">Edit</a>
                        <a href="index.php?r=products/index" class="btn btn-warning btn-round">Kembali</a>
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
                                    <td>Kode</td>
                                    <td width="10px">:</td>
                                    <td><?=$data->code?></td>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td><?=$data->name?> (<?=$data->shortname?>)</td>
                                </tr>
                                <tr>
                                    <td>Satuan</td>
                                    <td>:</td>
                                    <td><?=$data->unit->name?></td>
                                </tr>
                                <tr>
                                    <td>Kategori</td>
                                    <td>:</td>
                                    <td><?=$data->categories[0]->name?></td>
                                </tr>
                                <tr>
                                    <td>Gambar</td>
                                    <td>:</td>
                                    <td><img src="<?=$data->pic?>" alt="" width="300px" style="object-fit:cover"></td>
                                </tr>
                            </table>
                            <?php if($success_msg): ?>
                                <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>
                            <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                                <li class="nav-item submenu">
                                    <a onclick="set_active('#pills-home')" class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Harga</a>
                                </li>
                                <li class="nav-item submenu">
                                    <a onclick="set_active('#pills-profile')" class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Stok</a>
                                </li>
                            </ul>
                            <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                                <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <a href="index.php?r=product-prices/create&id=<?=$data->id?>" class="btn btn-primary">Tambah Harga</a>
                                    <div class="table-responsive table-hover table-sales">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th width="20px">#</th>
                                                    <th>Harga Normal</th>
                                                    <th>Harga Member</th>
                                                    <th>Diskon</th>
                                                    <th class="text-right"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(empty($data->prices)): ?>
                                                <tr>
                                                    <td colspan="5" style="text-align:center"><i>Tidak ada data</i></td>
                                                </tr>
                                                <?php endif ?>
                                                <?php 
                                                foreach($data->prices as $index => $price): 
                                                    $discount_price = 0;
                                                    if($price->discount_price)
                                                    {
                                                        $discount = $price->discount_price;
                                                        if($price->discount_type == 'fixed')
                                                            $discount_price = $price->base_price-$discount;
                                                        else
                                                            $discount_price = $price->base_price-($price->base_price*$discount/100);
                                                    }
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?=$index+1?>
                                                    </td>
                                                    <td><?=number_format($price->base_price)?></td>
                                                    <td><?=number_format($price->member_price)?></td>
                                                    <td>
                                                        <?=number_format($price->discount_price)?>
                                                        <?php if($price->discount_price): ?>
                                                        <br>
                                                        <i>Diskon : <?=number_format($price->discount_price)?> <?=$price->discount_type=='fixed'?'':'%'?></i>
                                                        <?php endif ?>
                                                    </td>
                                                    <td>
                                                        <a href="index.php?r=product-prices/delete&id=<?=$price->id?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a>
                                                    </td>
                                                </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <a href="index.php?r=product-stocks/create&id=<?=$data->id?>" class="btn btn-primary">Tambah Stok</a>
                                    <div class="table-responsive table-hover table-sales">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th width="20px">#</th>
                                                    <th>Jumlah</th>
                                                    <th>Tanggal</th>
                                                    <th class="text-right"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(empty($data->stocks)): ?>
                                                <tr>
                                                    <td colspan="4" style="text-align:center"><i>Tidak ada data</i></td>
                                                </tr>
                                                <?php endif ?>
                                                <?php foreach($data->stocks as $index => $stock): ?>
                                                <tr>
                                                    <td>
                                                        <?=$index+1?>
                                                    </td>
                                                    <td><?=number_format($stock->qty)?></td>
                                                    <td><?=$stock->created_at?></td>
                                                    <td>
                                                        <?php if($stock->qty >= 0): ?>
                                                        <a href="index.php?r=product-stocks/delete&id=<?=$stock->id?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a>
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
        </div>
    </div>
    <script>
        var _active = '#pills-home'
        if(localStorage.getItem('tab_active'))
        {
            _active = localStorage.getItem('tab_active')
        }
        
        document.querySelector(_active).classList.add('active')
        document.querySelector(_active).classList.add('show')
        document.querySelector(_active+'-tab').classList.add('active')
        document.querySelector(_active+'-tab').classList.add('show')

        function set_active(el)
        {
            localStorage.setItem('tab_active',el)
        }
    </script>
<?php load_templates('layouts/bottom') ?>