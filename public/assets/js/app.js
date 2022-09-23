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
        '    <h1 style="margin: 0">WarShip</h1>\n' +
        '    <h3 style="margin: 0">Cyber Gaming ROOM</h3>\n' +
        '\n' +
        '    <h3>Код активації:</h3>\n' +
        '    <p style="font-size: 32px; margin: 0">' + code + '</p>\n' +
        '\n' +
        '    <p>Ціна: ' + amount + ' грн</p>\n' +
        '    <p>Час гри: ' + time + ' хвилин</p>\n' +
        '    <p>Дата покупки: ' + date + '</p>\n' +
        '</div>\n' +
        '</body>\n' +
        '</html>');
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
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
