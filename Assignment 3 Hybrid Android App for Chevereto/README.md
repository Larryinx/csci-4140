# Chevereto Hybrid Mobile App

## Overview

This project is a hybrid mobile application for the Chevereto image sharing platform. It utilizes a WebView to display and interact with the Chevereto web interface while providing a native experience for essential functionalities such as menu navigation and image uploading. The app is intended to enhance the user experience on mobile devices.

## Features

### Task 1: Development Environment and Access

- Docker compose file is in this directory.
- Configured Android Studio and an Android emulator (Pixel 7 Android 11) to run the application.
- Set the server address to `http://10.0.2.2:8080` to accommodate the Android emulator's networking.

### Task 2: Login Interface

- Created a native `LoginActivity` to collect user credentials.
- Utilized JavaScript injection in WebView to interact with Chevereto's original login page, filling in the credentials and submitting the form programmatically.
- Implemented logic to handle login success and failure notifications.
- Ensured persistence of WebView cookies to maintain user sessions.

### Task 3: Main Interface

- Hid the original Chevereto buttons for image uploading using JavaScript code injection in WebView.
- Added native "MENU" and "UPLOAD" buttons to the app bar, enhancing the mobile user interface.
- Implemented a JavaScript function to simulate clicking the hidden menu bar in Chevereto when the "MENU" button is tapped.
- Configured the "UPLOAD" button to navigate directly to the Chevereto upload page.
- Made sure that all navigation within the Chevereto platform occurs inside the WebView instead of opening in an external browser.

## Conclusion

Overall, I have finished almost all of the tasks except Supporting Local File Selection. 