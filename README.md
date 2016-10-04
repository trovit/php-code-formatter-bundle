# Php Code Formatter Bundle
[![Build Status](https://secure.travis-ci.org/trovit/php-code-formatter-bundle.png)](http://travis-ci.org/trovit/php-code-formatter-bundle) 

Symfony bundle which provides a basic system to organize and execute php code formatters.

## Installation

### Step 1: Require bundle using composer

```Shell
$ composer require trovit/php-formatter-bundle "^1.0"
```


### Step 2: Enable the bundle

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Trovit\PhpCodeFormatterBundle\PhpCodeFormatterBundle(),
        // ...
    );
}
```

### Step 3: Configure the bundle  

There are only 2 parameters available at the moment:

- *temporary_path* _(string)_: temporary path where the temporary files should be created. This is necessary for those formatter libraries that

- *formatter_services* _(string[])_: each string represents de reference name of a formatter service

Example:
```yaml
// app/config.yml

trovit_php_code_formatter:
    temporary_path: "%kernel.root_dir%/../test/resources/"
    formatter_services:
      - 'trovit.php_code_formatter.formatters.php_cs_formatter'
```
### Step 3 (optional): Create your own Formatter

When you need to format your code and the formatters in the bundle don't satisfy your needs (different code language, formats, etc...) there is the possibility to create a new Formatter class by implementing the Formatter interface (_Trovit\PhpCodeFormatter\Formatters\Formatter_) and implement its method *formatCode*

After that, you have to register the formatter as a service and add the service reference name in the config (_check step 3_).


## Usage

Get the manager service wherever you want to call the method *execute* with the bad format code as a parameter. It will return the formatted code.

Example with the PhpCsFormatter:
```php
// src/AppBundle/Controller/DefaultController.php

$code = '<?php                    
                 echo "hola"; ?>';
$this->get('trovit.php_code_formatter.managers.formatter_manager')->execute($code);

// This will return
/*<?php

        echo 'hola';
*/
```

## List of available formatters

- *PhpCsFormatter*: Wrapper of PHP CS Fixer by Fabien Potencier & Dariusz Rumiński


Feel free to add as much as you want and contribute by PR!
