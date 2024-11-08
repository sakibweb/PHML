# PHML
## PHML is PHP to HTML Library

PHML is a PHP library that simplifies the process of generating HTML markup dynamically. It provides a fluent and intuitive API for creating HTML elements, managing attributes, and handling nested structures.  It also offers powerful features to convert HTML to array/JSON formats and vice-versa, making it easy to work with HTML structures in your PHP applications.

## Features

* **Dynamic HTML Element Generation:** Create any HTML element using method calls matching the tag name.
* **Attribute Management:** Easily add, modify, and remove attributes of HTML elements.
* **Nested Structures:**  Create complex, nested HTML structures with ease.
* **Array-Based Markup (ARML):** Define HTML structures using PHP arrays.
* **JSON-Based Markup (JSML):** Generate HTML from JSON data.
* **HTML to Array/JSON Conversion:** Convert HTML from a string, file, or URL into a PHP array or JSON.
* **PHP Array to PHP Code:** Generate PHP code from a PHP array representing HTML structure.
* **Automatic Tag Closure:** Correctly handles self-closing tags and ensures proper HTML structure.
* **Resource Link Fixing:** Automatically fixes resource links (CSS, JS, images) when loading HTML from a URL.


## Installation

You can simply include the `PHML.php` file in your project:

```php
require_once 'PHML.php';
```

## Usage

### Dynamic Element Creation

```php
echo PHML::div(['class' => 'container'], 'Hello, world!');

// Output: <div class="container">Hello, world!</div>

echo PHML::img(['src' => 'image.jpg', 'alt' => 'An image']);

// Output: <img src="image.jpg" alt="An image" />

echo PHML::a(['href' => 'https://example.com'], 'Click me');

// Output: <a href="https://example.com">Click me</a>


// Create elements with unclosed tags.
echo PHML::p(['end' => false], 'This paragraph tag is not closed');

// Output: <p>This paragraph tag is not closed
```

### Nested Structures

```php
echo PHML::div(
    ['id' => 'main'],
    PHML::h1('My Title'),
    PHML::p(['class' => 'description'], 'Some text here.'),
    PHML::ul(
        PHML::li('Item 1'),
        PHML::li('Item 2')
    )
);
```

### Array-Based Markup (ARML)

```php

$html_structure = [
    'div' => [
        'class' => 'container',
        'inner' => [
            'h1' => 'My Heading',
            'p' => 'Some paragraph text.'
        ]
    ]
];

echo PHML::arml($html_structure); 
```

### JSON-Based Markup (JSML)

```php
$json = '{
  "div": {
    "class": "content",
    "inner": {
      "h2": "Title",
      "p": "Text content"
    }
  }
}';

echo PHML::jsml($json);
```


### HTML to Array/JSON

```php
// From a URL (with resource link fixing)
$array = PHML::html('https://www.example.com', true);  // Returns an array
$json = PHML::html('https://www.example.com', true, 'json'); // Returns a JSON string


// From a string
$htmlString = '<div id="mydiv"><h1>Hello</h1><p>World!</p></div>';
$array = PHML::html($htmlString);


// From a file
$array = PHML::html('path/to/file.html');
print_r($array);

// Specific function to load from a link and optionally get JSON output:
$array = PHML::htmlFromLink('https://www.example.com');
$json = PHML::htmlFromLink('https://www.example.com', true); // Get JSON output

```


### PHP Array to PHP Code

```php
$html_array = [
    'div' => [
        'class' => 'container',
        'inner' => 'Hello'
    ]
];

echo PHML::php($html_array);

// Output: 
// array(
//     'div' => array(
//         'class' => 'container',
//         'inner' => 'Hello',
//     ),
// );
// ?>

```


## Contributing

Contributions are welcome!  Please feel free to submit pull requests or open issues.

## License

This project is licensed under the MIT License.  See the LICENSE file for details.
