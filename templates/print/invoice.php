<div style="font-size:7px;">
    <br>
    <div style="text-align:center">
        <b><?=app('name')?></b><br>
        <?=app('address').'<br>'.app('phone')?>
    </div>
    <br>

    <table border="0" width="100%" style="width:100%;font-size:7px;">
        <tr>
            <td colspan="2" width="85" style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:5px 0px;">
            <?=date('d-m-Y H:i:s')?>
            </td>
            <td colspan="2" width="85" style="border-top:1px dashed #000;border-bottom:1px dashed #000;text-align:right;padding:5px 0px;">
            <?=$transaction->inv_code.' / '.substr($transaction->user->name,0,10)?>
            </td>
        </tr>
        <?php foreach($transaction->items as $item): ?>
        <tr>
            <td><?=$item->product->shortname?></td>
            <td></td>
            <td style="text-align:right;"><?=$item->qty?> x <?=number_format($item->price)?></td>
            <td style="text-align:right;"><?=number_format($item->subtotal)?></td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td colspan="4" style="border-top:1px dashed #000;">
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="padding:5px 0px;"><b>Total</b></td>
            <td style="padding:5px 0px;text-align:right;"><?=number_format($transaction->total)?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="border-top:1px dashed #000;padding:5px 0px;"><b>Bayar</b></td>
            <td style="border-top:1px dashed #000;padding:5px 0px;text-align:right;"><?=number_format($transaction->paytotal)?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:5px 0px;"><b>Kembalian</b></td>
            <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:5px 0px;text-align:right;"><?=number_format($transaction->return_total)?></td>
        </tr>
        <tr>
            <td colspan="4">
                <br><br>
                <div style="text-align:center">
                    <i>** Terimakasih telah berbelanja di <?=app('name')?> **</i>
                </div>
            </td>
        </tr>
    </table>
</div>