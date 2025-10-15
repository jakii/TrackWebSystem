document.addEventListener('DOMContentLoaded', function() {
    const printBtn = document.getElementById('printBtn');
    if (!printBtn) return; // PDF or button not present

    printBtn.addEventListener('click', function() {
        const cardBody = document.querySelector('.card-body');
        if (!cardBody) return;

        const clone = cardBody.cloneNode(true);
        clone.style.maxHeight = 'none';
        clone.style.overflow = 'visible';

        // Automatic page breaks
        const pageHeight = 1100; // approx A4
        let accumulatedHeight = 0;

        const elements = clone.querySelectorAll('div, table, img, pre, .bg-light');
        elements.forEach(el => {
            const elHeight = el.offsetHeight;
            if (accumulatedHeight + elHeight > pageHeight) {
                const breakDiv = document.createElement('div');
                breakDiv.style.pageBreakBefore = 'always';
                el.parentNode.insertBefore(breakDiv, el);
                accumulatedHeight = elHeight;
            } else {
                accumulatedHeight += elHeight;
            }
        });

        // Open print window
        const printWindow = window.open('', '', 'width=900,height=650');
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Preview</title>
                <style>
                    body { font-family: 'Times New Roman', serif; font-size:14px; line-height:1.6; padding:20px; }
                    img { max-width:100%; height:auto; display:block; margin:10px auto; page-break-inside: avoid; }
                    table { border-collapse: collapse; width: 100%; margin: 15px 0; page-break-inside: auto; }
                    table td, table th { border: 1px solid #000; padding: 6px 10px; }
                    tr { page-break-inside: avoid; page-break-after: auto; }
                    pre { white-space: pre-wrap; word-wrap: break-word; }
                    .bg-light { background-color: #f8f9fa !important; padding:15px; border-radius:5px; max-height: none !important; overflow: visible !important; }
                    h1, h2, h3, h4, h5, h6, p { page-break-inside: avoid; }
                </style>
            </head>
            <body onload="window.print(); window.close();">
                ${clone.outerHTML}
            </body>
            </html>
        `);
        printWindow.document.close();
    });
});
