function cetakAndroid(transaction, log = false)
{
    var formatter = new Intl.NumberFormat('en-US', {});

    var transactionItems = "[C]--------------------------------\n";
    transaction.items.forEach((item,index)=>{
        if(index == 0 || item.product.category.id != transaction.items[index-1].product.category.id)
            transactionItems += `[L]${item.product.category.name}\n`
        
        transactionItems += `[L]${item.product.shortname}\n`
        transactionItems += `[L]${item.qty} x ${formatter.format(item.price)} [R]${formatter.format(item.subtotal)}\n`
    })
    transactionItems += "[C]--------------------------------\n";

    var app = window.app

    var printText = "[C]<b>"+app.name+"</b>\n" +
                    "[C]"+app.address+"\n" +
                    "[C]"+app.phone+"\n" +
                    "[C]--------------------------------\n" +
                    "[C]"+transaction.created_at+"\n" +
                    transactionItems +
                    `[L]<b>Total</b> [R]${formatter.format(transaction.total)}\n` +
                    "[C]--------------------------------\n" +
                    `[L]<b>Bayar</b> [R]${formatter.format(transaction.paytotal)}\n` +
                    "[C]--------------------------------\n" +
                    `[L]<b>Kembalian</b> [R]${formatter.format(transaction.return_total)}\n` +
                    "[C]--------------------------------\n\n" +
                    "[C]"+app.footer_struk
                    ;

    if(log)
    {
        console.log(printText)
    }
    else
    {
        Android.printInvoice(printText);
    }
}