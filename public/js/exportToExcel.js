function hideRow(table,rowIndex){
    var elt = document.getElementById('liste_ecoles');
    for (var i = 0, row; row = elt.length; i++) {
        if(rowIndex==i){
            row.className ="hiddenclass";
        }
    }        
}

function ExportToExcel(type, fn, dl) {
var elt = document.getElementById('liste_ecoles');
hideRow(  elt,2);
var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1", ignoreHiddenRows: true });

    return dl ?
        XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
        XLSX.writeFile(wb, fn || ('liste_ecoles.' + (type || 'xlsx')));
}