<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

if(request() == 'POST')
{

    $handle  = fopen($_FILES['file']['tmp_name'], "r");

    // skip header
    $headers = fgetcsv($handle, 1000, ",");

    $user = auth();

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
    {

        $unit = $db->single('units',[
            'name'=>$data[3]
        ]);

        if(!$unit)
            $unit = $db->insert('units',[
                'name'=>$data[3],
                'code'=>$data[3]
            ]);

        $product = $db->insert('products',[
            'unit_id'=>$unit->id,
            'code' => $data[0],
            'name' => $data[1],
            'shortname'=>$data[2]
        ]);
        
        $category = $db->single('categories',[
            'name'=>$data[6]
        ]);

        if(!$category)
            $category = $db->insert('categories',[
                'name'=>$data[6],
                'code'=>$data[6]
            ]);

        $db->insert('product_categories',[
            'product_id' => $product->id,
            'category_id' => $category->id
        ]);

        $db->insert('product_prices',[
            'product_id' => $product->id,
            'base_price' => $data[4]
        ]);

        $db->insert('product_stocks',[
            'product_id' => $product->id,
            'qty' => $data[5]
        ]);
    }

    fclose($handle);

    set_flash_msg(['success'=>'Import Produk berhasil']);
    header('location:index.php?r=products/index');
}

return compact('success_msg');