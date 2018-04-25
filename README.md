# Template Component
![version](https://img.shields.io/badge/version-1.0.0-brightgreen.svg?style=flat-square "Version")
[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://github.com/flextype-components/template/blob/master/LICENSE)

Template Component provides basic template engine for native PHP templates.

### Installation

```
composer require flextype-components/template
```

### Usage

```php
use Flextype\Component\Template\Template;
```

Create a new template object and render it.
```php
// Create new template object
$template = new Template('blog/templates/backend/index');

// Assign some new variables
$template->assign('msg', 'Some message...');

// Get template
$output = $template->render();

// Display template
echo $output;
```

Template factory  
Create new template object, assign some variables
and displays the rendered template in the browser.
```php
Template::factory('blog/templates/backend/index')
     ->assign('msg', 'Some message...')
     ->display();
```

Include the template file and extracts the template variables before returning the generated output.
```php
// Get template
$output = $template->render();

// Display output
echo $output;
```

Displays the rendered template in the browser.
```php
$template->display();
```

## License
See [LICENSE](https://github.com/flextype-components/template/blob/master/LICENSE)
