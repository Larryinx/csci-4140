let camanJSButtonInserted = false;

function handleCamanJSEditor() {
  // Insert the editor interface at the top of the `#anywhere-upload-submit` container
  const submitContainer = document.querySelector('#anywhere-upload-submit');
  if (submitContainer) {
    submitContainer.insertAdjacentHTML('afterbegin', `
    <div id="camanjs-editor">
      <canvas id="camanjs-canvas" width="200" height="100"></canvas>
      <label>Brightness<input type="range" id="brightness" min="-100" max="100" value="0" /></label>
      <label>Contrast<input type="range" id="contrast" min="-100" max="100" value="0" /></label>
      <label>Saturation<input type="range" id="saturation" min="-100" max="100" value="0" /></label>
      <button id="original-image">Original</button>
      <button id="done-editing">Done</button>
      <button id="cancel-editing">Cancel</button>
    </div>
`);
  }

  function loadImageOnAnotherCanvas(originalCanvasSelector, newCanvasId) {
    const originalCanvas = document.querySelector(originalCanvasSelector);
    if (!originalCanvas) {
      console.error('Original canvas not found');
      return;
    }

    // Create a new image element
    var img = new Image();

    // Convert the original canvas content to a data URL
    img.src = originalCanvas.toDataURL();

    img.onload = function () {
      // Select or create the new canvas
      let newCanvas = document.getElementById(newCanvasId);
      if (!newCanvas) {
        newCanvas = document.createElement('canvas');
        newCanvas.id = newCanvasId;
        // Append the new canvas to the body or another container as needed
        document.body.appendChild(newCanvas);
      }

      newCanvas.width = img.width;
      newCanvas.height = img.height;

      // Ensure newCanvas is a canvas element before calling getContext
      if (newCanvas instanceof HTMLCanvasElement) {
        // Draw the image onto the new canvas
        const context = newCanvas.getContext('2d');
        context.drawImage(img, 0, 0);
      } else {
        console.error('newCanvas is not a canvas element');
      }
    }
  }

  // Example usage: Load the image from the first canvas in #anywhere-upload-queue to a new canvas with ID 'new-canvas-id'
  loadImageOnAnotherCanvas('#anywhere-upload-queue .queue-item .canvas', 'camanjs-canvas');

  // Add listeners for the filter sliders
  document.getElementById('brightness').addEventListener('input', updateFilters);
  document.getElementById('contrast').addEventListener('input', updateFilters);
  document.getElementById('saturation').addEventListener('input', updateFilters);

  // Function to update the image based on filter values
  function updateFilters() {
    Caman('#camanjs-canvas', function () {
      this.revert(false); // Revert to the original image
      this.brightness(parseInt(document.getElementById('brightness').value));
      this.contrast(parseInt(document.getElementById('contrast').value));
      this.saturation(parseInt(document.getElementById('saturation').value));
      this.render();
    });
  }

  // Button to revert to the original image
  document.getElementById('original-image').addEventListener('click', function () {
    Caman('#camanjs-canvas', function () {
      this.revert(true);
    });
    // reset the value of the sliders
    document.getElementById('brightness').value = 0;
    document.getElementById('contrast').value = 0;
    document.getElementById('saturation').value = 0;
  });

  function injectScriptFile(scriptName) {
    const scriptUrl = chrome.runtime.getURL(scriptName);
    const script = document.createElement('script');
    script.src = scriptUrl;
    script.onload = function () {
      this.remove(); 
    };
    (document.head || document.documentElement).appendChild(script);
  }

  setTimeout(() => {
    injectScriptFile('pageScript.js');
    console.log('Injected pageScript.js');
  }, 300);

  // Button to finalize edits and hide editor
  document.getElementById('done-editing').addEventListener('click', function () {
    var canvas = document.getElementById('camanjs-canvas');
    canvas.toBlob(function (blob) {
      var fileName = 'edited-image.png';
      var fileType = 'image/png'; // Or the original mime type if available
      var file = new File([blob], fileName, { type: fileType });

      // Dispatch a custom event with the file object
      var event = new CustomEvent('UpdateCHVFile', { detail: { file: file } });
      document.dispatchEvent(event);

      // Hide the editor and reset the UI
      hideEditor();
    }, 'image/png'); // Or the original image's mime type
  });


  // Button to cancel edits and hide editor
  document.getElementById('cancel-editing').addEventListener('click', function () {
    hideEditor();
  });

  function hideEditor() {
    // Logic to hide the editor and possibly revert the image to its original state
    const editor = document.getElementById('camanjs-editor');
    if (editor) {
      editor.remove();
    }
    insertCamanJSButton(submitContainer);
  }

}

function insertCamanJSButton(container) {
  // Check if our 'Edit with CamanJS' button already exists to avoid duplicates
  if (!container.querySelector('#edit-with-camanjs')) {
    let editButton = document.createElement('button');
    editButton.innerText = 'Edit with CamanJS';
    editButton.id = 'edit-with-camanjs';
    editButton.className = 'btn btn-big'; 
    // Add any event listeners or attributes needed for CamanJS interaction
    editButton.addEventListener('click', function () {
      // Logic to open the CamanJS editor
      handleCamanJSEditor();
      console.log('Edit with CamanJS button clicked');
      // Remove the button after it's clicked
      editButton.remove();
    });

    // Insert the 'Edit with CamanJS' button before the 'Upload' button within the container
    const uploadButton = container.querySelector('button[data-action="upload"]');
    if (uploadButton) {
      uploadButton.parentNode.insertBefore(editButton, uploadButton);
      console.log('Edit with CamanJS button added.');
    }
  }
}

const intervalID = setInterval(() => {
  chrome.storage.local.get('cheveretoHost', (data) => {
    if (data.cheveretoHost) {
      const uploadPageUrl = data.cheveretoHost + '/upload'; 
      if (window.location.href.startsWith(uploadPageUrl)) {
        // Select the container where the 'Upload' button is located
        const submitContainer = document.querySelector('#anywhere-upload-submit');
        if (submitContainer) {
          // Call the function to insert the 'Edit with CamanJS' button into the container
          insertCamanJSButton(submitContainer);
          camanJSButtonInserted = true;
        }
        if (camanJSButtonInserted) {
          clearInterval(intervalID);
        }
      }
    }
  });
}, 1000);

chrome.runtime.onMessage.addListener(function (request, sender, sendResponse) {
  if (request.action === "uploadImage") {
    const imageUrl = request.imageUrl;
    // Proceed to download the image and convert it to a File object
    downloadImageAndUpload(imageUrl);
    console.log('Image URL received:', imageUrl);
  }
});

function downloadImageAndUpload(imageUrl) {
  fetch(imageUrl)
    .then(response => response.blob())
    .then(blob => {
      const file = new File([blob], "uploaded-image.png", { type: blob.type });
      uploadFileToChevereto(file);
      console.log('Image uploaded:', file);
    })
    .catch(error => console.error('Error downloading image:', error));
}

function uploadFileToChevereto(file) {
  const fileInput = document.querySelector('#anywhere-upload-input');
  if (fileInput) {
    // Set the file to the input
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    fileInput.files = dataTransfer.files;

    // Manually trigger the change event
    fileInput.dispatchEvent(new Event('change', { bubbles: true }));
  }
}
