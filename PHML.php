<?php
/**
 * Render HTML elements using PHP with PHML
 * Author: Sakibur Rahman @sakibweb
 * Library: https://github.com/sakibweb/PHML
 * This class provides methods to generate HTML elements dynamically
 */
 
class PHML {
	
    /**
     * Generate HTML element
     *
     * @param string $tag The tag name of the HTML element
     * @param array $attributes Optional. An associative array of attributes for the element
     * @param string $value Optional. The inner content of the element
     * @param bool $end Optional. Whether to close the tag or not. Default is true.
     * @return string The generated HTML element
     */
    public static function element($tag, $attributes = [], $value = '', $end = true) {
        $html = "<$tag";
        foreach ($attributes as $key => $val) {
            // Exclude 'inner' and 'end' from attributes
            if ($key !== 'inner' && $key !== 'end') {
                $html .= " $key=\"$val\"";
            }
        }

        // Add value (inner content) if present
        if ($value !== '') {
            $html .= ">$value";
            // Add closing tag if 'end' is true or not provided
            if ($end) {
                $html .= "</$tag>";
            }
        } else {
            // Don't add closing tag for self-closing tags like <br>
            if (!in_array($tag, ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'])) {
                $html .= "></$tag>";
            } else {
                $html .= " />";
            }
        }
        return $html;
    }

	/**
	 * Magic method to handle dynamic method calls.
	 *
	 * @param string $name The name of the method called.
	 * @param array $arguments The arguments passed to the method.
	 * @return string|null The generated HTML element or null if no arguments provided.
	 */
    public static function __callStatic($name, $arguments) {
        if (!empty($arguments)) {
            $tag = $name;
            $attributes = [];
            $children = '';
            $end = true; // Default end value

            foreach ($arguments as $arg) {
                // If it's an array, it's attributes or inner content
                if (is_array($arg)) {
                    if (isset($arg['inner'])) {
                        $innerContent = $arg['inner'];
                        // If inner content is an array, recursively render it
                        if (is_array($innerContent)) {
                            foreach ($innerContent as $inner) {
                                $children .= self::arml($inner);
                            }
                        } else {
                            // If inner content is a string, set it as inner HTML
                            $children .= $innerContent;
                        }
                    } elseif (isset($arg['end'])) {
                        // Check if 'end' key is provided
                        $end = (bool)$arg['end'];
                    } else {
                        // Otherwise, merge attributes
                        $attributes = array_merge($attributes, $arg);
                    }
                } elseif (is_string($arg)) {
                    // If it's a string, it's the value
                    $children .= $arg;
                } elseif (is_object($arg) && get_class($arg) === 'HTML') {
                    // If it's an HTML object, it's a nested element
                    $children .= $arg->arml();
                }
            }

            // Render the element
            return self::element($tag, $attributes, $children, $end);
        }
    }

	/**
	 * Parse a single HTML element from an indexed array.
	 *
	 * @param array $element The indexed array representing the HTML element.
	 * @return string The generated HTML element or an empty string if the element is invalid.
	 */
	private static function parseElement($element) {
		if (!is_array($element)) {
			return ''; // Invalid element
		}

		// Separate tag, attributes, inner content, and 'end' key
		$tag = key($element);
		$content = current($element);
		$attributes = [];
		$children = '';
		$end = true; // Default end value

		if (is_array($content)) {
			if (isset($content['inner'])) {
				$innerContent = $content['inner'];
				// If inner content is an array, recursively render it
				if (is_array($innerContent)) {
					$children .= self::arml($innerContent);
				} else {
					// If inner content is a string, set it as inner HTML
					$children .= $innerContent;
				}
				unset($content['inner']);
			}
			// Set 'end' key value
			if (isset($content['end'])) {
				$end = (bool)$content['end'];
				unset($content['end']);
			}
			// Set remaining array elements as attributes
			$attributes = $content;
		} else {
			// If content is not an array, treat it as inner HTML
			$children = $content;
		}

		// Generate HTML for the element
		return self::element($tag, $attributes, $children, $end);
	}

	/**
	 * Parse a single HTML element from an associative array.
	 *
	 * @param string $tag The HTML tag name.
	 * @param mixed $content The content of the HTML element.
	 *                      Can be either an associative array or a string.
	 * @return string The generated HTML element.
	 */
	private static function parseNestedElement($tag, $content) {
		$attributes = [];
		$children = '';
		$end = true; // Default end value

		// Separate attributes, inner content, and 'end' key
		if (is_array($content)) {
			if (isset($content['inner'])) {
				$innerContent = $content['inner'];
				// If inner content is an array, recursively render it
				if (is_array($innerContent)) {
					$children .= self::arml($innerContent);
				} else {
					// If inner content is a string, set it as inner HTML
					$children .= $innerContent;
				}
				unset($content['inner']);
			}
			// Set 'end' key value
			if (isset($content['end'])) {
				$end = (bool)$content['end'];
				unset($content['end']);
			}
			// Set remaining array elements as attributes
			$attributes = $content;
		} else {
			// If content is not an array, treat it as inner HTML
			$children = $content;
		}

		// Generate HTML for the element
		return self::element($tag, $attributes, $children, $end);
	}

    /**
     * Render an array of HTML elements
     *
     * @param array $elements An associative array representing HTML elements and their attributes
     * @return string The generated HTML string
     */
	public static function arml($elements) {
		if (!is_array($elements)) {
			return '';
		}

		$html = '';
		foreach ($elements as $key => $content) {
			// Check if the key is numeric, indicating an indexed array
			if (is_numeric($key)) {
				// Treat the content as a single HTML element
				$html .= self::parseElement($content);
			} else {
				// Treat the content as an associative array representing nested HTML elements
				$html .= self::parseNestedElement($key, $content);
			}
		}

		return $html;
	}

    /**
     * Render HTML elements from JSON data
     *
     * @param string $json_data JSON-encoded string representing HTML elements
     * @return string The generated HTML string
     */
    public static function jsml($json_data) {
        $data = json_decode($json_data, true);

        return self::arml($data);
    }
}
?>
