# TicketparkFileBundle

This Symfony2 bundle ads functionalities to handle files.

## Functionalities
* FileHandler (Service)
    * Handle cached files
    * Download remote files to local file system
    
* FileEncoder (Service and TwigExtension)
    * Get encoded file contents (e.q. base64 encoded)

* FileProvider (Service and TwigExtension)
	* Define a list of files and call them by an identifier key.
	This simplifies exchanging static files in one central place in your configuration

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
Use the file handler service in a controller to get files from urls, cache files and read files from cache.

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

Using the file encoder in a controller:
``` php
$fileEncoder = $this->get('ticketpark.file.encoder');
$base64EncodedFileContent = $fileEncoder->base64($filePathOrUrl);
```
    
There is also a Twig extension, example:
``` php
@font-face {
    font-family: "FancyFont";
    src: url({{ pathToFancyFontFile|base64 }});
}
```

## Usage of FileProvider

### Setup

The file provider needs some setup. First define a root directory as well as a list of files.

``` yml
// app/config/parameters.yml
provided_images_dir:   '%kernel.root_dir%/../src/Acme/Bundle/YourBundle/Resources/images/'
provided_images:
    'logo':   'logo.png'
    'mascot': 'mascot.jpg'
```

Then define a custom service:

``` yml
// YourBundle/Resources/config/services.yml
services:
    acme.image.provider:
        class: Ticketpark\FileBundle\FileProvider\FileProvider
        arguments: [%provided_images_dir%, %provided_images%]
```

Now you can use the file provider service in a controller:
``` php
$imageProvider = $this->get('acme.image.provider');
$pathToLogoFile = $imageProvider->get('logo');
```

### File Provider as Twig Extension
You can also use the file provider as a Twig extension. However, you need to do some work first (if anybody knows a way to avoid this, please let me know).

First create a Twig extension which extends the Twig extension provided by this bundle. The important part is the function name you define in `new \Twig_SimpleFunction('image', array($this, 'getFile'))`. In this case it is `image`, however you may call it anything you want.

```php
<?php

namespace Acme\Bundle\YourBundle\Twig;

use Ticketpark\FileBundle\Twig\FileProviderExtension;

class ImageProviderExtension extends FileProviderExtension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('image', array($this, 'getFile')),
        );
    }

    public function getName()
    {
        return 'acme_image_provider_extension';
    }
}
```

Then register the Twig extension as a service

```yml
acme.twig.image_provider_extension:
    class: Acme\Bundle\YourBundle\Twig\ImageProviderExtension
    arguments: [@acme.image.provider]
    tags:
        - { name: twig.extension }
```

Now you may use it in a template:
```html
<img src="{{ image('logo') }}">
```

You can also combine functions and filters (this is standard Twig behaviour);
```html
<img src="{{ image('logo')|base64 }}">
```



## License


This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
