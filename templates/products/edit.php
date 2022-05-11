<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Edit Produk : <?=$data->name?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data produk</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
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
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="">Kode</label>
                                    <input type="text" name="products[code]" class="form-control" value="<?=$data->code?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" name="products[name]" class="form-control" value="<?=$data->name?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama Singkat</label>
                                    <input type="text" name="products[shortname]" class="form-control" value="<?=$data->shortname?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Satuan</label>
                                    <select name="products[unit_id]" class="form-control" id="" required>
                                        <option value="">- Pilih -</option>
                                        <?php foreach($units as $unit): ?>
                                        <option value="<?=$unit->id?>" <?=$unit->id==$data->unit_id?'selected=""':''?>><?=$unit->name?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Kategori</label>
                                    <select name="category" class="form-control" id="" required>
                                        <option value="">- Pilih -</option>
                                        <?php foreach($categories as $category): ?>
                                        <option value="<?=$category->id?>" <?=in_array($category->id,$cats)?'selected=""':''?>><?=$category->name?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Gambar</label>
                                    <input type="file" name="file" class="form-control">
                                    <img src="<?=$data->pic?>" alt="" width="300px" style="object-fit:cover">
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