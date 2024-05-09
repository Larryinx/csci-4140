
(function() {
    // Function to update CHV uploader file
    function updateCHVUploaderFile(file) {
        if (CHV.fn.uploader && CHV.fn.uploader.files) {
            CHV.fn.uploader.files[0] = file;
            console.log('CHV uploader file updated:', file);
            // Add any additional logic needed to complete or refresh the upload process in Chevereto
        } else {
            console.error('CHV uploader is not defined or does not have a files property');
        }
    }

    // Event Listener for 'ReplaceCHVUploaderFile'
    document.addEventListener('ReplaceCHVUploaderFile', function(e) {
        updateCHVUploaderFile(e.detail.file);
    });

    // Event Listener for 'UpdateCHVFile'
    document.addEventListener('UpdateCHVFile', function(e) {
        updateCHVUploaderFile(e.detail.file);
    });

    // Optional: Check if CHV object is available and perform actions accordingly
    function waitForCHV() {
        if (typeof CHV !== 'undefined' && CHV.fn && CHV.fn.uploader) {
            // CHV is available, perform any initialization if needed
            console.log('CHV is available.');
        } else {
            // CHV is not available yet, retry after a delay
            console.log('CHV not available yet, retrying...');
            setTimeout(waitForCHV, 500);
        }
    }

    waitForCHV();
})();
