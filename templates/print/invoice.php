<div>
    <br>
    <div style="text-align:center">
        <h3><?=app('name')?></h3>
        <?=app('address').'<br>'.app('phone')?>
    </div>
    <br>

    <table border="0" style="width:100%">
        <tr>
            <td colspan="2" style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:5px 0px;">
            <?=date('d-m-Y H:i:s')?>
            </td>
            <td colspan="2" style="border-top:1px dashed #000;border-bottom:1px dashed #000;text-align:right;padding:5px 0px;">
            <?=$invoice->code.' / '.substr($invoice->creator->name,0,10)?>
            </td>
        </tr>
        <?php foreach($invoice->items as $item): ?>
        <tr>
            <td>
                <?=$item->product->shortname?><br>
                <?=$item->qty?> x <?=number_format($item->price)?>
            </td>
            <td></td>
            <td></td>
            <td style="text-align:right;">
                <br>
                <?=number_format($item->subtotal)?>
            </td>
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
            <td style="padding:5px 0px;text-align:right;"><?=number_format($invoice->total)?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="border-top:1px dashed #000;padding:5px 0px;"><b>Bayar</b></td>
            <td style="border-top:1px dashed #000;padding:5px 0px;text-align:right;"><?=number_format($invoice->paytotal)?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:5px 0px;"><b>Kembalian</b></td>
            <td style="border-top:1px dashed #000;border-bottom:1px dashed #000;padding:5px 0px;text-align:right;"><?=number_format($invoice->return_total)?></td>
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