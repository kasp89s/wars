function CallPrint(code, amount, time, date) {
    var WinPrint = window.open('','','left=50,top=50,width=800,height=640,toolbar=0,scrollbars=1,status=0');
    WinPrint.document.write('<!DOCTYPE html>\n' +
        '<html lang="en">\n' +
        '<head>\n' +
        '    <meta charset="UTF-8">\n' +
        '    <title>Title</title>\n' +
        '</head>\n' +
        '<body>\n' +
        '<div style="text-align: center">\n' +
        // '<img src="http://wars.local/storage/bc934a5b3de3e65d8cef93ac9d3aee48.jpg" style="width: 200px;">\n' +
        '    <h1 style="margin: 0">WarShip</h1>\n' +
        '    <h3 style="margin: 0">Cyber Gaming ROOM</h3>\n' +
        '\n' +
        '    <h3>Код активації:</h3>\n' +
        '    <p style="font-size: 24px; margin: 0">' + code + '</p>\n' +
        '\n' +
        '    <p>Ціна: ' + amount + ' грн</p>\n' +
        '    <p>Час гри: ' + time + ' хвилин</p>\n' +
        '    <p>Дата покупки: ' + date + '</p>\n' +
        '</div>\n' +
        '</body>\n' +
        '</html>');

    setTimeout(function () {
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
        WinPrint.close();
    }, 100);
}

$('#create-receipt').on('click', function () {
   $.post(
       '/admin/create-receipt',
       {
           time: $('#time').val()
       },
       function (response) {
           CallPrint(response.code, response.amount, response.time, response.date);
           window.location.reload();
       },
       'json'
   );
});
