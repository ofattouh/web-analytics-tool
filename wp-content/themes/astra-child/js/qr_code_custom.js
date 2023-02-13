//----------------------------------------------------------------------------------------
// Garvity Forms Custom JS Script

jQuery(function($){

    // Print custom page content when print QR button is clicked
    $('.kaya-osir-signup-print-btn').on('click', function(e){
        var printableArea = document.getElementById("printableArea");
        var printableArea2 = document.getElementById("printableArea2");
        var WindowQRContents = printableArea.innerHTML;
        printableArea.style.display = "block";
        printableArea2.style.display = "none";
        
        var WindowQRCodePrint = window.open('', '', 'width=1920,height=1080');
        WindowQRCodePrint.document.write(WindowQRContents);
        WindowQRCodePrint.document.close();
        WindowQRCodePrint.focus();
        WindowQRCodePrint.print();
        WindowQRCodePrint.close();

        // console.log(printableArea);
	});

});
