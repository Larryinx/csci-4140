# Web Instagram Project

Welcome to the ***Web Instagram Project***. This document provides an overview of the project's structure, key components, and development process. 

**CLASS: CSCI4140**

**NAME: LI YINXI**

**SID: 1155160255**

## Website Link 

**Important:** Please visit the project's website hosted on Render at the following URL:  
[Project Website on Render](https://one155160255.onrender.com)  
- Also you can click website deployed using this repo here: https://one155160255.onrender.com

## Clarification

**Important** Please read all of this section to understand some of the things that are happening on the site.

- `'index.php'`:
  - There is no sign for private or public images. The gallary will display the private (belongs to the corresponding user) and public images by the latest creation time order
  - Only a login user could access to the file upload system.
  - You have to upload a file with a correct type (".jpeg, .jpg, .png, .gif"). If you upload an incorrect file type, such as ".abc", the website will nevigated to the home page and generate a warning.
  - Public image is the default type. Press "Private" button to upload the private image.
  - Only the administrator (admin) could access to the "System Management" link. After click on that link, there are the options to deside whether to initialize the system in the bottom of the home page.
  - After initialization, all images and non-admin users will be removed.
- `login.php`:
  - There are one administrator and two normal users in default whose usernames and passwords are shown in the login page.
- `editor.php`
  - On this page, you can make the image both black and white with a border. If you're not happy with the changes you've made, click the "original" button to restore the original photo.

## Directory Structure and Functionality 

Below is an outline of the project's directory structure along with a description of the contents and functionality of each directory and file.

- `/web` - Root directory containing all the PHP scripts and the stored images for the web application.
  - `/images` - Stores all user-uploaded photos, categorized as either public or private.
  - `db_connect.php` - Handles the PostgreSQL database connection.
  - `index.php` - Serves as the homepage and displays the photo album.
  - `login.php` - Manages the user login interface.
  - `login_handler.php` - Processes the login credentials and establishes user sessions.
  - `upload.php` - Manages the uploading of images to the server.
  - `editor.php` - Provides the photo editing functionality, allowing users to add borders and convert images to black and white or discard the image.
  - `init.php` - Contains the logic to re-initialize the system by removing all images and non-admin users.
- `/Dockerfile` - Contains the configuration for deploying the application using Docker.

## System Building Procedure 

The system was built using the following procedure and key components:

1. **Setup Development Environment** - Configured the local development environment with necessary tools and dependencies.
2. **Database Integration** - We integrated a PostgreSQL for robust data management and retrieval.
3. **Coding Standards** - Followed best practices in coding standards to ensure readability and maintainability.
4. **Version Control** - Utilized Git for version control, with systematic commits and descriptive messages.
5. **Image Processing** - Implemented using ImageMagick for PHP.
6. **Testing and Debugging** - Wrote unit and integration tests to ensure the stability and reliability of the system.
7. **Deployment** - Deployed the application on the Render server using continuous deployment pipelines.

## Accomplishments and Bonus Request

We would like to highlight the following accomplishments which we believe warrant a bonus:

- **File type checking** Our upload system includes robust file type validation to ensure that only genuine image files are accepted. The upload handler meticulously verifies the MIME type against the file extension, rejecting any discrepancies to maintain system integrity. 
- **System Initialization** The initialization process encompasses the deletion of all photos and related database records, as well as the recreation of necessary database tables and initial records, ensuring a clean slate for the application upon confirmation.
- **Input Validation** Every input to the system would be checked for correctness.

## Partially Completed Work (None)

All of the tasks has been done!

We appreciate your understanding and consideration when grading the project.

---

Thank you for reviewing our project. For further questions or clarifications, please do not hesitate to contact us.
