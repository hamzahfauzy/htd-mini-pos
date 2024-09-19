function printContent(invoice)
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

    var printText = "[C]"+app.name+"\n" +
                    "[C]"+app.address+"\n" +
                    "[C]"+app.phone+"\n" +
                    "[C]--------------------------------\n" +
                    "[C]"+invoice.notes+"\n" +
                    "[C]--------------------------------\n" +
                    "[C]"+invoice.created_at+"\n" +
                    invoiceItems +
                    `[L]Total [R]${formatter.format(invoice.total)}\n` +
                    "[C]--------------------------------\n" +
                    `[L]Bayar [R]${formatter.format(invoice.total-invoice.remaining_payment)}\n` +
                    "[C]--------------------------------\n" +
                    `[L]Sisa [R]${formatter.format(invoice.remaining_payment)}\n` +
                    "[C]--------------------------------\n\n" +
                    "[C]"+app.footer_struk
                    ;
    return printText;
}
function cetakAndroid(invoice, log = false)
{
    const printText = printContent(invoice)

    if(log)
    {
        console.log(printText)
    }
    else
    {
        Android.printInvoice(printText);
    }
}

function cetakRawBt(invoice, log = false)
{
    const printText = replaceWithEscSequences(printContent(invoice))
    console.log(printText)

    if(log)
    {
        console.log(printText)
    }
    else
    {
        var S = "#Intent;scheme=rawbt;";
        var P =  "package=ru.a402d.rawbtprinter;end;";
        var textEncoded = encodeURI(printText);
        window.location.href="intent:"+textEncoded+S+P;
    }
}

function replaceWithEscSequences(text) {
    
    // Handle lines that have both [L] and [R]
    text = text.replace(/\[L\](.*?)\[R\](.*)/g, function(match, leftText, rightText) {
        const totalLength = 31; // Assuming 32 characters per line for 58mm paper
        const leftLength = leftText.trim().length; // Trim extra spaces
        const rightLength = rightText.trim().length;
        const spaces = totalLength - (leftLength + rightLength); // Calculate spaces between left and right text
        
        // Make sure there is at least one space between left and right text
        return leftText + ' '.repeat(spaces > 0 ? spaces : 1) + rightText;
    });

    // Replace [C], [L], [R] with corresponding alignment ESC sequences
    text = text.replace(/\[C\]/g, '\x1B\x61\x01');  // Center alignment
    text = text.replace(/\[L\]/g, '\x1B\x61\x00');  // Left alignment
    text = text.replace(/\[R\]/g, '\x1B\x61\x02');  // Right alignment
  
    // Replace horizontal lines with actual dashes
    text = text.replace(/-{32,}/g, '--------------------------------');
  
    // Add newline for each break
    text = text.replace(/\n/g, '\n');
  
    return text;
}