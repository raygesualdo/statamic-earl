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
        'version'    => '1.1',
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
    public function __call($method, $args) 
    {
        // Fetch source, attributes, tag type and file URL
        $src = $this->fetchParam('src', Config::getTheme(), null, false, false);     
        $attr = $this->getAttributes();
        $tag  = $this->fetchParam('tag', false, null, false, true);
        $file = $this->buildUrl($src, $method); 
        
        // Backwards compatibility with old helpers
        if ($tag === 'true') $tag = $method;
        
        switch ($tag)
        {
            case "js":
            case "script":
            case "scripts":
            case "javascript":
                return '<script src="' . $file . '" ' . $attr . '></script>';
            case "css":
            case "style":
            case "styles":
            case "stylesheet":
                return '<link href="' . $file . '" rel="stylesheet" ' . $attr . '>';
            case "img":
            case "image":
            case "images":
                return '<img src="' . $file . '" ' . $attr . '>';
            default:
                return $file;
        }
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
    
    /**
     * getAttributes function
     * 
     * This function parses the attributes passed with the "attr" tag.
     *
     * @return string
     */
    private function getAttributes()
    {
		$attributes_string = '';

		if ($attr = $this->fetchParam('attr', false, null, false, false)) {
			$attributes_array = Helper::explodeOptions($attr, true);
			foreach ($attributes_array as $key => $value) {
				$attributes_string .= " {$key}='{$value}'";
			}
		}
      
        return $attributes_string;
    }
}