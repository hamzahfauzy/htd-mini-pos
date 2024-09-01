function cetakAndroid(invoice, log = false)
{
    var formatter = new Intl.NumberFormat('en-US', {});

    var invoiceItems = "[C]--------------------------------\n";
    invoice.items.forEach((item,index)=>{
        if(index == 0 || item.product.category.id != invoice.items[index-1].product.category.id)
            invoiceItems += `[L]${item.product.category.name}\n`
        
        invoiceItems += `[L]${item.product.shortname}\n`
        invoiceItems += `[L]${item.qty} x ${formatter.format(item.price)} [R]${formatter.format(item.subtotal)}\n`
    })
    invoiceItems += "[C]--------------------------------\n";

    var app = window.app

    var printText = "[C]<b>"+app.name+"</b>\n" +
                    "[C]"+app.address+"\n" +
                    "[C]"+app.phone+"\n" +
                    "[C]--------------------------------\n" +
                    "[C]"+invoice.notes+"\n" +
                    "[C]--------------------------------\n" +
                    "[C]"+invoice.created_at+"\n" +
                    invoiceItems +
                    `[L]<b>Total</b> [R]${formatter.format(invoice.total)}\n` +
                    "[C]--------------------------------\n" +
                    `[L]<b>Bayar</b> [R]${formatter.format(invoice.paytotal)}\n` +
                    "[C]--------------------------------\n" +
                    `[L]<b>Kembalian</b> [R]${formatter.format(invoice.return_total)}\n` +
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