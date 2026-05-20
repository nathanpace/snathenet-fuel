<?php

/**
 * DotEnv
 * 
 * Class for enabling use of .env file for variables
 */
class DotEnv
{
    /**
     * The directory where the .env file can be located.
     *
     * @var string
     */
    protected $path;


    /**
     * Constructor
     * 
     * Sets path to .env file
     * 
     * @access public
     * 
     * @param string $path path to .env file
     */
    public function __construct(string $path)
    {
        // Throw exception if no file exists on the supplied path
        if(!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
        }

        // Assign path to class variable
        $this->path = $path;
    }

    /**
     * load
     * 
     * Load variables from .env file into $_ENV superglobal
     * 
     * @access public
     * 
     * @param string $path path to .env file
     * @return void
     */
    public function load() :void
    {

        // Throw exception if .env file is not readable
        if (!is_readable($this->path)) {
            throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
        }

        // Read all the lines from the file into an array and iterate
        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {

            // Ignore comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Explode each line into separate variables on the '=' sign as a delimeter
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Add value to $_ENV superglobal if it doesn't already exist
            if (!array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
            }
        }
    }
}