<?php
/**
 * Plugin_earl
 * Links to files on another domain
 *
 * @author Ray Gesualdo <ray@rjgesualdo.com>
 * @link   https://github.com/raygesualdo/statamic-earl
 */

class Plugin_earl extends Plugin 
{

    var $meta = array(
        'name'       => 'E.A.R.L.',
        'version'    => '0.1',
        'author'     => 'Ray Gesualdo',
        'author_url' => 'http://rjgesualdo.com'
    );
    
    /**
     * __construct() piggyback
     * 
     * This function grabs '_earl_base' from the E.A.R.L. config
     * file and formats it for use later in the plugin. 
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->base = $this->fetchConfig('_earl_base', Config::getSiteRoot(), null, false, false);
        if (!preg_match('/^((http(s)?:)?\/?\/)[a-zA-Z0-9]/', $this->base))
        {
            $this->base = "//" . $this->base;
        }
        $this->base = rtrim($this->base, '/');
    }

    /**
     * Function overloading
     * 
     * This function allows users to declare their own methods using
     * the {{ earl:mymethod }} syntax. Settings for the corresponding
     * method can be added to the E.A.R.L. config file using the
     * '_earl_path_mymethod' and '_earl_ext_mymethod' syntax.
     * See earl.yaml for more information.
     *    
     * @return string
     */
    static public function __callStatic($method, $args) 
    {
        //TODO: Get this working
    }

    /**
     * index function
     * 
     * This function process the parameters and calls buildUrl().
     *
     * @return string
     */
    public function index() 
    {
        // Fetch source from tag and exit if empty
        $src = $this->fetchParam('src', false, null, false, false);    
        if (!$src)
        {
            return NULL;
        } 

        // Fetch settings group
        $group = $this->fetchParam('group', false, null, false, true);    
        
        return $this->buildUrl($src, $group);
    }

    /**
     * js function
     * 
     * This function process the parameters and calls buildUrl(). 
     * An HTML tag can optionally be returned.
     *
     * @return string
     */
    public function js()
    {
        // Fetch source from tag and exit if empty
        $src = $this->fetchParam('src', false, null, false, false);    
        if (!$src)
        {
            return NULL;
        }     
        
        // Get file URL and tag boolean
        $file = $this->buildUrl($src, 'js');        
        $tag = $this->fetchParam('tag', false, null, true, false);

        return ($tag) ? '<script src="' . $file . '"></script>' : $file;
    }

    /**
     * css function
     * 
     * This function process the parameters and calls buildUrl(). 
     * An HTML tag can optionally be returned.
     *
     * @return string
     */
    public function css()
    {
        // Fetch source from tag and exit if empty
        $src = $this->fetchParam('src', false, null, false, false);    
        if (!$src)
        {
            return NULL;
        } 
        
        // Get file URL and tag boolean
        $file = $this->buildUrl($src, 'css');
        $tag = $this->fetchParam('tag', false, null, true, false);
        
        return ($tag) ? '<link href="' . $file . '" rel="stylesheet">' : $file;
    }

    /**
     * img function
     * 
     * This function process the parameters and calls buildUrl(). 
     * An HTML tag with ALT text can optionally be returned.
     *
     * @return string
     */
    public function img()
    {
        // Fetch source from tag and exit if empty
        $src = $this->fetchParam('src', false, null, false, false);    
        if (!$src)
        {
            return NULL;
        }        
        
        // Get file URL and tag boolean
        $file = $this->buildUrl($src, 'img');
        $tag = $this->fetchParam('tag', false, null, true, false);
        
        // Get alt text and build alt tag
        $alt = $this->fetchParam('alt', null, null, false, false);
        if ($alt) 
        {
            $alt = ' alt="' . $alt . '"';
        }

        return ($tag) ? '<img src="' . $file . '" ' . $alt . '>' : $file;
    }
    
    /**
     * buildUrl function
     * 
     * This function builds the file URL using parameters
     * fetched previously.
     *
     * @param string  $src  name for file
     * @param string  $group  settings group to use (optional)
     * @return string
     */
    private function buildUrl($src, $group = NULL)
    {
        // Exit if $src is empty or format  
        if (!$src)
        {
            return NULL;
        } 
        else 
        {
            $src = URL::format($src);        
        }

        // Fetch path in config and format 
        $pathSetting = "_earl_path_" . $group;
        $path = $this->fetchConfig($pathSetting, false, null, false, false);
        if ($path)
        {
            $path = URL::format($path);    
        }

        // Fetch extension in config and format
        $extSetting = "_earl_ext_" . $group;
        $ext = $this->fetchConfig($extSetting, false, null, false, false);
        if ($ext & !preg_match("/^\./", $ext))
        {
            $ext = "." . $ext;
        }

        // Add $ext to $src if it's not already present
        if (!preg_match("(\{$ext})", $src))
        {
            $src .= $ext;
        }

        // Return fully formed URL
        return $this->base . $path . $src;       
    }

}