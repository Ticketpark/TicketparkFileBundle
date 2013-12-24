# TicketparkFileBundle

This bundle ads functionalities to handle file caching, downloading, and encoding.

## Functionalities
* FileHandler (Service)
    * Handle cached files
    * Download remote file to local file system
    
* FileEncoder (Service and TwigExtension)
	* Get encoded file contents

## Installation

Add FOSUserBundle in your composer.json:

```js
{
    "require": {
        "ticketpark/file-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update ticketpark/file-bundle
```

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Ticketpark\FileBundle\TicketparkFileBundle(),
    );
}
```
## Usage of FileHandler


``` php
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
$params = array('foo' => bar); // use this to cache versions of files, eq. image in different sizes
if (!$pathToLocalFile = $fileHandler->fromCache($fileIdentifier, $params)) {
	// the file was not found in cache
}

// Cache a file
$fileHandler = $this->get('ticketpark.file.handler');
$params = array('foo' => bar); // use this to cache versions of files, eq. image in different sizes
$pathToLocalFile = $fileHandler->cache($fileContents, $fileIdentifier, $params);
```
    
## Usage of FileEncoder


``` php
// base64 encode file content
$fileEncoder = $this->get('ticketpark.file.encoder');
$base64EncodedFileContent = $fileEncoder->base64($filePathOrUrl);
```
    
There is also a Twig extension, example:
``` css
@font-face {
    font-family: "FancyFont";
    src: url({{ pathToFancyFontFile|base64 }});
}
```

## License


This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
