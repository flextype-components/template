<?php

/**
 * @package Flextype Components
 *
 * @author Sergey Romanenko <awilum@yandex.ru>
 * @link http://components.flextype.org
 *
 * For the full copyright and license information, please template the LICENSE
 * file that was distributed with this source code.
 */

namespace Flextype\Component\Template;

class Template
{
    /**
     * Path to template file.
     *
     * @var string
     */
    protected $template_file;

    /**
     * Template variables.
     *
     * @var array
     */
    protected $vars = [];

    /**
     * Global template variables.
     *
     * @var array
     */
    protected static $global_vars = [];

    /**
     * The output.
     *
     * @var string
     */
    protected $output;

    /**
     * Template extension.
     *
     * @var string
     */
    public static $template_ext = '.php';

    /**
     * Create a new template object.
     *
     * // Create new template object
     * $template = new Template('blog/templates/backend/index');
     *
     * // Assign some new variables
     * $template->assign('msg', 'Some message...');
     *
     * // Get template
     * $output = $template->render();
     *
     * // Display template
     * echo $output;
     *
     * @param string $template      Name of the template file
     * @param array  $variables Array of template variables
     */
    public function __construct(string $template, array $variables = [])
    {
        // Is template file exists ?
        if (!file_exists($template . Template::$template_ext)) {
            throw new RuntimeException(vsprintf("%s(): The '%s' template does not exist.", array(__METHOD__, $template)));
        }

        // Set template file
        $this->template_file = $template . Template::$template_ext;

        // Set template variables
        $this->vars = $variables;
    }

    /**
     * Template factory
     *
     * // Create new template object, assign some variables
     * // and displays the rendered template in the browser.
     * Template::factory('blog/templates/backend/index')
     *     ->assign('msg', 'Some message...')
     *     ->display();
     *
     * @param  string $template      Name of the template file
     * @param  array  $variables Array of template variables
     * @return Template
     */
    public static function factory(string $template, array $variables = [])
    {
        return new Template($template, $variables);
    }

    /**
     * Assign a template variable.
     *
     * $template->assign('msg', 'Some message...');
     *
     * @param  string  $key    Variable name
     * @param  mixed   $value  Variable value
     * @param  boolean $global Set variable available in all templates
     * @return Template
     */
    public function assign(string $key, $value, bool $global = false)
    {
        // Assign a new template variable (global or locale)
        if ($global === false) {
            $this->vars[$key] = $value;
        } else {
            Template::$global_vars[$key] = $value;
        }

        return $this;
    }

    /**
     * Include the template file and extracts the template variables before returning the generated output.
     *
     * // Get template
     * $output = $template->render();
     *
     * // Display output
     * echo $output;
     *
     * @param  string $filter Callback function used to filter output
     * @return string
     */
    public function render($filter = null) : string
    {
        // Is output empty ?
        if (empty($this->output)) {

            // Extract variables as references
            extract(array_merge($this->vars, Template::$global_vars), EXTR_REFS);

            // Turn on output buffering
            ob_start();

            // Include template file
            include($this->template_file);

            // Output...
            $this->output = ob_get_clean();
        }

        // Filter output ?
        if ($filter !== null) {
            $this->output = call_user_func($filter, $this->output);
        }

        // Return output
        return $this->output;
    }

    /**
     * Displays the rendered template in the browser.
     *
     * $template->display();
     *
     */
    public function display()
    {
        echo $this->render();
    }

    /**
     * Magic setter method that assigns a template variable.
     *
     * @param string $key   Variable name
     * @param mixed  $value Variable value
     */
    public function __set(string $key, $value)
    {
        $this->vars[$key] = $value;
    }

    /**
     * Magic getter method that returns a template variable.
     *
     * @param  string $key Variable name
     * @return mixed
     */
    public function __get(string $key)
    {
        if (isset($this->vars[$key])) {
            return $this->vars[$key];
        }
    }

    /**
     * Magic isset method that checks if a template variable is set.
     *
     * @param  string  $key Variable name
     * @return boolean
     */
    public function __isset(string $key)
    {
        return isset($this->vars[$key]);
    }

    /**
     * Magic unset method that unsets a template variable.
     *
     * @param string $key Variable name
     */
    public function __unset(string $key)
    {
        unset($this->vars[$key]);
    }

    /**
     * Method that magically converts the template object into a string.
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->render();
    }
}
