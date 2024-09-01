<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header bg-primary-gradient">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Detail Aplikasi</h2>
                        <h5 class="text-white op-7 mb-2">Update detail aplikasi</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if($success_msg): ?>
                            <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="app[id]" value="<?=$data->id?>">
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" name="app[name]" class="form-control" value="<?=$data->name?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat</label>
                                    <textarea name="app[address]" id="" required class="form-control mb-2" placeholder="Alamat Disini..."><?=$data->address?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Telepon/Handphone/Whatsapp</label>
                                    <input type="tel" name="app[phone]" class="form-control" value="<?=$data->phone?>">
                                </div>
                                <div class="form-group">
                                    <label for="">E-Mail</label>
                                    <input type="email" name="app[email]" class="form-control" value="<?=$data->email?>">
                                </div>
                                <div class="form-group">
                                    <label for="">POS Mode</label>
                                    <select name="app[pos_mode]" id="" class="form-control" required>
                                        <option value="">- Pilih -</option>
                                        <?php foreach(config('pos_mode') as $label => $mode): ?>
                                        <option <?= $data->pos_mode == $label ? 'selected=""' : '' ?>><?=$label?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Gambar</label>
                                    <input type="file" name="app[icon_url]" class="form-control mb-2">
                                    <img src="<?=$data->icon_url?>" alt="" width="100px" style="object-fit:cover">
                                </div>
                                <div class="form-group">
                                    <label for="">Footer Struk</label>
                                    <textarea name="app[footer_struk]" class="form-control"><?=$data->footer_struk?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Cash drawer status</label>
                                    <select name="app[cash_drawer_status]" id="" class="form-control" required>
                                        <option value="">- Pilih -</option>
                                        <option <?= $data->cash_drawer_status == 'On' ? 'selected=""' : '' ?>>On</option>
                                        <option <?= $data->cash_drawer_status == 'Off' ? 'selected=""' : '' ?>>Off</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Fee Sales</label>
                                    <input type="number" name="app[fee_sales]" class="form-control" value="<?=$data->fee_sales?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Tipe Fee Sales</label>
                                    <select name="app[fee_sales_type]" id="" class="form-control" required>
                                        <option value="">- Pilih -</option>
                                        <option <?= $data->fee_sales_type == 'Percent' ? 'selected=""' : '' ?>>Percent</option>
                                        <option <?= $data->fee_sales_type == 'Fixed' ? 'selected=""' : '' ?>>Fixed</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Pajak</label>
                                    <input type="number" name="app[tax]" class="form-control" value="<?=$data->tax?>">
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