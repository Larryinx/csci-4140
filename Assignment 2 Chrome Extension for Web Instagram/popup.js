document.addEventListener('DOMContentLoaded', () => {
    let cheveretoHostInput = document.getElementById('chevereto-host');
    let saveButton = document.getElementById('save-button');
    
    // Load the current Chevereto host setting and set it in the input field
    chrome.storage.local.get('cheveretoHost', (data) => {
        if (data.cheveretoHost) {
            cheveretoHostInput.value = data.cheveretoHost;
        }
    });

    saveButton.addEventListener('click', () => {
        let cheveretoHost = cheveretoHostInput.value;
        if (cheveretoHost) {
            chrome.storage.local.set({'cheveretoHost': cheveretoHost}, () => {
                console.log('Chevereto host saved.');
            });
        }
    });
});
