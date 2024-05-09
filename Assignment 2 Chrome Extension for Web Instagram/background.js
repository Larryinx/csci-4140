chrome.runtime.onInstalled.addListener(() => {
    chrome.contextMenus.create({
        id: "upload-to-chevereto",
        title: "Upload to Chevereto",
        contexts: ["image"],
        documentUrlPatterns: ["http://*/*", "https://*/*"]
    });

    // Set the default Chevereto host if it is not already set
    chrome.storage.local.get('cheveretoHost', (data) => {
        if (!data.cheveretoHost) {
            chrome.storage.local.set({ 'cheveretoHost': 'http://localhost' });
        }
    });
});

// Listen for the context menu item click
chrome.contextMenus.onClicked.addListener((info, tab) => {
    if (info.menuItemId === "upload-to-chevereto") {
        // Get the Chevereto host URL from storage
        chrome.storage.local.get('cheveretoHost', (data) => {
            const cheveretoHost = data.cheveretoHost || 'http://localhost';
            // Open a new tab with the Chevereto upload page
            chrome.tabs.create({ url: cheveretoHost + '/upload', active: true }, function(newTab) {
                // Send a message to the content script with the image URL after a delay
                setTimeout(() => {
                    chrome.tabs.sendMessage(newTab.id, { action: "uploadImage", imageUrl: info.srcUrl });
                }, 1500); // Adjust delay as needed
            });
        });
    }
});


chrome.runtime.onMessage.addListener(function (request, sender) {
    if (request.action === "updateCHVUploaderFile") {
        chrome.scripting.executeScript({
            target: { tabId: sender.tab.id },
            function: updateCHVUploaderFile,
            args: [request.blob]
        });
    }
});

function updateCHVUploaderFile(blob) {
    // Convert the blob to a File object
    var file = new File([blob], "edited-image.png", { type: "image/png" });

    // Inject script to update CHV.fn.uploader.files[0] with this file
    var scriptContent = `
      document.dispatchEvent(new CustomEvent('UpdateCHVFile', { detail: { file: ${JSON.stringify(file)} }}));
    `;

    var script = document.createElement('script');
    script.textContent = scriptContent;
    (document.head || document.documentElement).appendChild(script);
    script.remove();
}
