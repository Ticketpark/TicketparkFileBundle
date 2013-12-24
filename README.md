# TicketparkFileBundle

This bundle as helpful functionalities to handle custom file caching and file downloading.

## Functionalities
* FileHandler (Service)
    * Handle cached files
    * Download remote file to local file system
    
* FileEncoder (Service and TwigExtension)
	* Get encoded file contents

## Usage of FileHandler


	<?php
	
	// Get a file path
	// Regardless whether it is already a local file or a remote url (so you don't have to care about it)
    $fileHandler = $this->get('ticketpark.file.handler');
    $pathToLocalFile = $fileHandler->get($filePathOrUrl);
    
    // Download a file (and automatically cache it locally)
    $fileHandler = $this->get('ticketpark.file.handler');
    $pathToLocalFile = $fileHandler->download($url);
    
    // Get a file from cache
    $fileHandler = $this->get('ticketpark.file.handler');
    $fileIdentifier = … //this could be anything, eq. custom string, file path or url - up to you!
    if (!$pathToLocalFile = $fileHandler->fromCache($fileIdentifier)) {
    	// the file was not found in cache
    }
    
    // Cache a file
    $fileHandler = $this->get('ticketpark.file.handler');
    $params = array('foo' => bar); // use this to cache versions of files, eq. image in different sizes
    $pathToLocalFile = $fileHandler->cache($fileContents, $fileIdentifier, $params);

    
## Usage of FileEncoder


	<?php
	
	// base64 encode file content
    $fileEncoder = $this->get('ticketpark.file.encoder');
    $base64EncodedFileContent = $fileEncoder->base64($filePathOrUrl);
    
There is also a Twig extension, example:

    @font-face {
        font-family: "FancyFont";
        src: url({{ pathToFancyFontFile|base64 }});
    }


## License


This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
