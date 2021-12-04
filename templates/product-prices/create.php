<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Buat Harga Produk : <?=$data->name?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data harga produk</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="index.php?r=products/view&id=<?=$_GET['id']?>" class="btn btn-warning btn-round">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <input type="hidden" name="prices[product_id]" value="<?=$_GET['id']?>">
                                <div class="form-group">
                                    <label for="">Harga Normal</label>
                                    <input type="number" name="prices[base_price]" class="form-control" value="0" min="0" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Harga Member</label>
                                    <input type="number" name="prices[member_price]" class="form-control" value="0" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="">Diskon</label>
                                    <input type="text" name="prices[discount_price]" class="form-control" value="0" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="">Tipe Diskon</label>
                                    <select name="prices[discount_type]" class="form-control" id="">
                                        <?php foreach(['fixed','%'] as $type): ?>
                                        <option value="<?=$type?>"><?=$type?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>