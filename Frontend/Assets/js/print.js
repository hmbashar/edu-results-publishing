function cbeduPrintResult(tableId) {
    let cbeduTableContent = document.getElementById(tableId).outerHTML;
    let cbeduPrintWindow = window.open('', '', 'height=600,width=800');
    
    cbeduPrintWindow.document.write('<html><head><title>Print Table</title>');

    // Print-specific CSS
    cbeduPrintWindow.document.write('<style type="text/css" media="print">');
    cbeduPrintWindow.document.write('table { width: 100%; border-collapse: collapse; }');
    cbeduPrintWindow.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }');
    cbeduPrintWindow.document.write('thead { background-color: #f2f2f2; }');
    // Add more CSS rules as needed
    cbeduPrintWindow.document.write('</style>');

    cbeduPrintWindow.document.write('</head><body>');
    cbeduPrintWindow.document.write(cbeduTableContent);
    cbeduPrintWindow.document.write('</body></html>');
    cbeduPrintWindow.document.close();
    cbeduPrintWindow.print();
}