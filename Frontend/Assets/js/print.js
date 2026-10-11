/**
 * Open a designed A4 result sheet for Print / Save as PDF.
 *
 * @param {string} tableId Element id of the result container.
 */
function cbeduPrintResult(tableId) {
    var source = document.getElementById(tableId);
    if (!source) {
        return;
    }

    var settings = (typeof cbedu_print_object !== 'undefined' && cbedu_print_object) ? cbedu_print_object : {};
    var clone = source.cloneNode(true);

    // Strip print UI and interactive bits from the clone.
    clone.querySelectorAll('.cbedu-print-button-container, script').forEach(function (el) {
        el.remove();
    });

    var photoSrc = '';
    var photoEl = source.querySelector('.cbedu-result-collage-std-image img, .cbedu-result-sheet-collage-logo img');
    if (photoEl && photoEl.getAttribute('src')) {
        photoSrc = photoEl.getAttribute('src');
    }

    // Prefer student photo from thumbnail area if present in clone.
    var studentPhoto = clone.querySelector('.cbedu-result-collage-std-image img');
    if (studentPhoto && studentPhoto.getAttribute('src')) {
        photoSrc = studentPhoto.getAttribute('src');
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

    var contactBits = [collegePhone, collegeEmail].filter(Boolean).join('  ·  ');
    var printedOn = new Date().toLocaleString();

    var logoHtml = collegeLogo
        ? '<img class="cbedu-print-header__logo is-circle" src="' + escapeAttr(collegeLogo) + '" alt="">'
        : '<div class="cbedu-print-header__logo" aria-hidden="true"></div>';

    var photoHtml = photoSrc
        ? '<img class="cbedu-print-header__photo" src="' + escapeAttr(photoSrc) + '" alt="">'
        : '<div class="cbedu-print-header__photo" aria-hidden="true"></div>';

    var headerHtml =
        '<header class="cbedu-print-header">' +
            logoHtml +
            '<div class="cbedu-print-header__meta">' +
                (collegeName ? '<h1 class="cbedu-print-header__college">' + escapeHtml(collegeName) + '</h1>' : '') +
                (collegeReg ? '<p class="cbedu-print-header__reg">' + escapeHtml(settings.regLabel || 'College Registration') + ': ' + escapeHtml(collegeReg) + '</p>' : '') +
                (contactBits ? '<p class="cbedu-print-header__contact">' + escapeHtml(contactBits) + '</p>' : '') +
            '</div>' +
            photoHtml +
        '</header>' +
        '<h2 class="cbedu-print-title"><span>' + escapeHtml(sheetTitle) + '</span></h2>';

    var footerHtml =
        '<div class="cbedu-print-signatures">' +
            '<div class="cbedu-print-signatures__item">' + escapeHtml(signatureLeft) + '</div>' +
            '<div class="cbedu-print-signatures__item">' + escapeHtml(signatureMid) + '</div>' +
            '<div class="cbedu-print-signatures__item">' + escapeHtml(signatureRight) + '</div>' +
        '</div>' +
        '<footer class="cbedu-print-footer">' +
            '<p class="cbedu-print-footer__note">' + escapeHtml(footerNote) + '</p>' +
            '<p>' + escapeHtml(printedLabel) + ': ' + escapeHtml(printedOn) + '</p>' +
        '</footer>';

    var printWindow = window.open('', '_blank', 'noopener,noreferrer,width=900,height=1100');
    if (!printWindow) {
        window.print();
        return;
    }

    var doc = printWindow.document;
    doc.open();
    doc.write(
        '<!DOCTYPE html><html><head>' +
        '<meta charset="utf-8">' +
        '<meta name="viewport" content="width=device-width, initial-scale=1">' +
        '<title>' + escapeHtml(sheetTitle) + '</title>' +
        (printCss ? '<link rel="stylesheet" href="' + escapeAttr(printCss) + '">' : '') +
        '</head><body>' +
        '<div class="cbedu-print-doc">' +
            headerHtml +
            '<div class="cbedu-print-body"></div>' +
            footerHtml +
        '</div>' +
        '</body></html>'
    );
    doc.close();

    var bodyMount = doc.querySelector('.cbedu-print-body');
    if (bodyMount) {
        bodyMount.appendChild(doc.importNode ? doc.importNode(clone, true) : clone);
    }

    // Wait for stylesheet + images, then print.
    var triggerPrint = function () {
        try {
            printWindow.focus();
            printWindow.print();
        } catch (e) {
            // no-op
        }
    };

    var stylesheetReady = !printCss;
    var linkEl = printCss ? doc.querySelector('link[rel="stylesheet"]') : null;

    if (linkEl) {
        linkEl.onload = function () {
            stylesheetReady = true;
            maybePrint();
        };
        linkEl.onerror = function () {
            stylesheetReady = true;
            maybePrint();
        };
        // Fallback if onload never fires.
        setTimeout(function () {
            stylesheetReady = true;
            maybePrint();
        }, 800);
    }

    function maybePrint() {
        if (!stylesheetReady) {
            return;
        }
        var images = Array.prototype.slice.call(doc.images || []);
        if (!images.length) {
            setTimeout(triggerPrint, 120);
            return;
        }
        var pending = images.length;
        var done = function () {
            pending -= 1;
            if (pending <= 0) {
                setTimeout(triggerPrint, 120);
            }
        };
        images.forEach(function (img) {
            if (img.complete) {
                done();
            } else {
                img.onload = done;
                img.onerror = done;
            }
        });
        setTimeout(function () {
            if (pending > 0) {
                triggerPrint();
            }
        }, 1500);
    }

    if (stylesheetReady) {
        maybePrint();
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
