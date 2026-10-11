/**
 * Open a designed A4 result sheet for Print / Save as PDF.
 *
 * @param {string} tableId Element id of the result container.
 */
function cbeduPrintResult(tableId) {
    var source = document.getElementById(tableId);
    if (!source) {
        window.alert('Result content was not found to print.');
        return;
    }

    var settings = (typeof cbedu_print_object !== 'undefined' && cbedu_print_object) ? cbedu_print_object : {};
    var clone = source.cloneNode(true);
    var nodes = clone.querySelectorAll('.cbedu-print-button-container, script');
    for (var i = 0; i < nodes.length; i++) {
        if (nodes[i].parentNode) {
            nodes[i].parentNode.removeChild(nodes[i]);
        }
    }

    var photoSrc = '';
    var photoEl = source.querySelector('.cbedu-result-collage-std-image img');
    if (!photoEl) {
        photoEl = source.querySelector('.cbedu-result-sheet-collage-logo img');
    }
    if (photoEl && photoEl.getAttribute('src')) {
        photoSrc = photoEl.getAttribute('src');
    }

    var collegeName = settings.collegeName || '';
    var collegeReg = settings.collegeReg || '';
    var collegePhone = settings.collegePhone || '';
    var collegeEmail = settings.collegeEmail || '';
    var collegeLogo = settings.collegeLogo || '';
    var sheetTitle = settings.sheetTitle || 'Academic Result Sheet';
    var printCss = settings.printCss || '';
    var printedLabel = settings.printedLabel || 'Printed on';
    var signatureLeft = settings.signatureLeft || 'Prepared by';
    var signatureMid = settings.signatureMid || 'Checked by';
    var signatureRight = settings.signatureRight || 'Controller of Examinations';
    var footerNote = settings.footerNote || 'This is a computer-generated result sheet. Verify details with the institute if needed.';
    var regLabel = settings.regLabel || 'College Registration';

    var contactBits = [];
    if (collegePhone) {
        contactBits.push(collegePhone);
    }
    if (collegeEmail) {
        contactBits.push(collegeEmail);
    }
    var contactLine = contactBits.join('  ·  ');
    var printedOn = new Date().toLocaleString();

    var logoHtml = collegeLogo
        ? '<img class="cbedu-print-header__logo is-circle" src="' + escapeAttr(collegeLogo) + '" alt="">'
        : '<div class="cbedu-print-header__logo" aria-hidden="true"></div>';

    var photoHtml = photoSrc
        ? '<img class="cbedu-print-header__photo" src="' + escapeAttr(photoSrc) + '" alt="">'
        : '<div class="cbedu-print-header__photo" aria-hidden="true"></div>';

    var fallbackCss =
        'body{margin:0;padding:16px;font-family:Arial,Helvetica,sans-serif;color:#1a202c;background:#fff;}' +
        '.cbedu-print-doc{max-width:900px;margin:0 auto;}' +
        '.cbedu-print-header{display:flex;justify-content:space-between;align-items:center;gap:16px;border-bottom:3px solid #0f4c81;padding-bottom:12px;margin-bottom:16px;}' +
        '.cbedu-print-header__meta{text-align:center;flex:1;}' +
        '.cbedu-print-header__college{margin:0 0 4px;font-size:22px;color:#0f4c81;}' +
        '.cbedu-print-header__reg,.cbedu-print-header__contact{margin:0;font-size:12px;color:#4a5568;}' +
        '.cbedu-print-header__logo,.cbedu-print-header__photo{width:72px;height:72px;object-fit:contain;border:1px solid #cbd5e0;}' +
        '.cbedu-print-header__logo.is-circle{border-radius:50%;}' +
        '.cbedu-print-title{text-align:center;color:#0f4c81;text-transform:uppercase;letter-spacing:.06em;font-size:16px;}' +
        '.cbedu-print-title span{border:1.5px solid #1769aa;padding:4px 12px;display:inline-block;}' +
        'table{width:100%;border-collapse:collapse;margin:0 0 14px;table-layout:auto;}' +
        'th,td{border:1px solid #718096;padding:8px;font-size:12px;vertical-align:middle;white-space:normal;word-break:normal;}' +
        'th{background:#edf2f7;text-align:left;min-width:90px;}' +
        'td{min-width:70px;}' +
        '.cbedu-result-student-information-heading,.cbedu-result-student-subject-heading,thead{background:#0f4c81!important;color:#fff!important;}' +
        '.cbedu-result-student-information-heading h4,.cbedu-result-student-subject-heading h4,thead th{color:#fff!important;margin:0;padding:8px 10px;}' +
        '.cbedu-result-student-information-area,.cbedu-result-student-subject-area{border:1px solid #0f4c81;margin-bottom:14px;}' +
        'td.highlight{background:#d6ebf8!important;color:#0f4c81;font-weight:700;}' +
        '.cbedu-status-badge{display:inline-block;padding:2px 8px;border:1px solid #03543f;background:#def7ec;color:#03543f;border-radius:999px;font-weight:700;}' +
        '.cbedu-print-button-container,.cbedu-result-ver-banner-area{display:none!important;}' +
        '.cbedu-print-signatures{display:flex;justify-content:space-between;gap:16px;margin-top:28px;}' +
        '.cbedu-print-signatures__item{flex:1;text-align:center;border-top:1px solid #718096;padding-top:36px;font-size:12px;font-weight:600;}' +
        '.cbedu-print-footer{display:flex;justify-content:space-between;gap:12px;margin-top:16px;padding-top:10px;border-top:1px solid #cbd5e0;font-size:11px;color:#4a5568;}' +
        '@media print{body{padding:0;}@page{size:A4;margin:12mm 14mm;}}';

    var html = '';
    html += '<!DOCTYPE html><html><head><meta charset="utf-8">';
    html += '<meta name="viewport" content="width=device-width, initial-scale=1">';
    html += '<title>' + escapeHtml(sheetTitle) + '</title>';
    html += '<style type="text/css">' + fallbackCss + '</style>';
    if (printCss) {
        html += '<link rel="stylesheet" href="' + escapeAttr(printCss) + '">';
    }
    html += '</head><body><div class="cbedu-print-doc">';
    html += '<header class="cbedu-print-header">';
    html += logoHtml;
    html += '<div class="cbedu-print-header__meta">';
    if (collegeName) {
        html += '<h1 class="cbedu-print-header__college">' + escapeHtml(collegeName) + '</h1>';
    }
    if (collegeReg) {
        html += '<p class="cbedu-print-header__reg">' + escapeHtml(regLabel) + ': ' + escapeHtml(collegeReg) + '</p>';
    }
    if (contactLine) {
        html += '<p class="cbedu-print-header__contact">' + escapeHtml(contactLine) + '</p>';
    }
    html += '</div>';
    html += photoHtml;
    html += '</header>';
    html += '<h2 class="cbedu-print-title"><span>' + escapeHtml(sheetTitle) + '</span></h2>';
    html += '<div class="cbedu-print-body">' + clone.outerHTML + '</div>';
    html += '<div class="cbedu-print-signatures">';
    html += '<div class="cbedu-print-signatures__item">' + escapeHtml(signatureLeft) + '</div>';
    html += '<div class="cbedu-print-signatures__item">' + escapeHtml(signatureMid) + '</div>';
    html += '<div class="cbedu-print-signatures__item">' + escapeHtml(signatureRight) + '</div>';
    html += '</div>';
    html += '<footer class="cbedu-print-footer">';
    html += '<p class="cbedu-print-footer__note">' + escapeHtml(footerNote) + '</p>';
    html += '<p>' + escapeHtml(printedLabel) + ': ' + escapeHtml(printedOn) + '</p>';
    html += '</footer>';
    html += '</div></body></html>';

    // Do not use noopener — it returns null / a sealed window and breaks document.write.
    var printWindow = window.open('', 'cbeduPrintWindow', 'width=920,height=1100');
    if (!printWindow) {
        window.alert('Please allow pop-ups to print the result sheet.');
        return;
    }

    printWindow.document.open();
    printWindow.document.write(html);
    printWindow.document.close();
    printWindow.focus();

    var printed = false;
    var doPrint = function () {
        if (printed) {
            return;
        }
        printed = true;
        try {
            printWindow.focus();
            printWindow.print();
        } catch (err) {
            // Leave the designed sheet open if the print dialog fails.
        }
    };

    // Give the popup time to render content + optional external CSS.
    if (printWindow.document.readyState === 'complete') {
        setTimeout(doPrint, 300);
    } else {
        printWindow.onload = function () {
            setTimeout(doPrint, 200);
        };
        setTimeout(doPrint, 800);
    }
}

function escapeHtml(value) {
    return String(value == null ? '' : value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function escapeAttr(value) {
    return escapeHtml(value).replace(/`/g, '&#96;');
}
