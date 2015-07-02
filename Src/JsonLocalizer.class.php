<?php
/**
* DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
*             Version 2, December 2004
*
* Copyright (C) 2015 SkyzohKey <uid:23@hack-free.net>
*
* Everyone is permitted to copy and distribute verbatim or modified
* copies of this license document, and changing it is allowed as long
* as the name is changed.
*
*     DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
* TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
*
* 0. You just DO WHAT THE FUCK YOU WANT TO.
**/

/**
*   @method string getLang() - Get the current lang.
*   @method bool   setLang(string $langCode) - Set the lang to use.
*   @method object render(string $filePath) - Render the specified file and process lang file.
**/
class JsonLocalizer
{
    // Default lang to use in case of errors.
    private $defaultLang = 'en';

    // Lang file related stuff.
    private $langsPath = '';
    private $langsExt = 'json';

    // Current lang related stuff.
    private $currentLang = 'en';
    private $currentLangJson = null;
    private $currentLangInfos = null;

    // Current file related stuff.
    private $currentFile = '';
    private $currentFilePath = '';

    // Availables langs to use;
    private $availablesLangs = [];

    /**
    *   @function __construct
    *   @param string $langsPath - Path of the lang files.
    *   @param string $langsExt - Extension of the lang files.
    *   @param string $currentLang - Default lang to use.
    *   @return $this
    **/
    public function __construct ($langsPath, $langsExt = 'json', $currentLang = 'en')
    {
        // If $langsPath is NOT a directory throw an error.
        if (!is_dir($langsPath))
            throw new Exception("Directory $langsPath doesn't exists.", 1);

        // If $currentLang does NOT match a lang file throw an error.
        if (!file_exists($langsPath.'lang-'.$currentLang.'.'.$langsExt))
            throw new Exception("Lang file <pre>".$langsPath.'lang-'.$currentLang.'.'.$langsExt."</pre> doesn't exists.", 1);

        // Define global shit.
        $this->langsPath = $langsPath;
        $this->langsExt = ($this->langsExt != $langsExt) ? $langsExt : $this->langsExt;
        $this->currentLang = ($this->currentLang != $currentLang) ? $currentLang : $this->currentLang;
        $this->defaultLang = $this->currentLang;

        // Get all the file that match /lang-*.json/s pattern and stock them in self::availablesLangs.
        $this->availablesLangs = array_values(array_diff(glob($this->langsPath . "lang-*.json"), glob($this->langsPath . "lang-*.json", GLOB_ONLYDIR)));

        return $this;
    }

    /**
    *   @function getLang
    *   @return string
    **/
    public function getLang ()
    {
    	return $this->currentLang;
    }

    /**
    *   @function setLang
    *   @param string $lang - Lang to use for next render.
    *   @return boolean
    **/
    public function setLang ($lang)
    {
        /**
        *   Ternaries conditions.
        *
        *   If self::currentLang isn't equals to $lang then,
        *   self::currentLang equals $lang else,
        *   self::currentLang equals self::currentLang.
        **/
    	$this->currentLang = ($this->currentLang != $lang) ? $lang : $this->currentLang;
        return ($this->currentLang === $lang);
    }

    /**
    *   @function render
    *   @param string $filePath - Path of the file to localize.
    *   @param string $textToRender - If not null, render the text.
    *   @return self
    **/
    public function render ($filePath, $textToRender = null)
    {
        // Decode the current lang file and stock it to an array (flag 1).
        $this->currentLangJson = json_decode($this->loadLangFile($this->currentLang), 1);

        if ($filePath != null && $textToRender == null)
        {
            // If file doesn't exists throw an error.
            if (!file_exists($filePath))
                throw new Exception("File <pre>$filePath</pre> doesn't exists.", 1);

            // Load the current file from a file.
            $this->currentFile = $this->loadPageFile($filePath);
            $this->currentFilePath = $filePath;
        }
        else
        {
            // Load the current file from a string.
            $this->currentFile = $textToRender;
        }

        // Save lang file infos and delete the key in the self::currentLangJson array.
        $this->currentLangInfos = $currentLangJso['_lang'];
        unset($currentLangJson->_lang);

        /**
        *   If we can match the "/{(.*?)}/s" pattern in the current page then,
        *   use a callback to perform the operations over the current match.
        **/
        try
        {
            $html = preg_replace_callback('/{(.*?)}/s', function ($matches)
            {
            	$tag = $matches[1];
    	    	$indexs = explode('.', $tag);
    	    	$tmp = $this->currentLangJson;

                // For each index, while iteration count is less than index's count.
    	    	for ($i = 0, $l = count($indexs); $i < $l; $i++)
    	    	{
                    /**
                    *   If we can't match a key in the json file then,
                    *   Set $tmp as null and return.
                    **/
    	    		if (!@array_key_exists($indexs[$i], $tmp))
    	    		{
    	    			$tmp = null;
    	    			break;
    	    		}

                    // TODO: use pointers here instead of this shit.
    	    		$tmp = $tmp[$indexs[$i]];

    	    	}

                /**
                *   If we found a match with a json key then,
                *   return the value that will replace the current match.
                **/
    	    	if ($tmp != null)
    	    		return $tmp;
                // Else, return the match without changing it.
                else
    	    		return '{'.$tag.'}';
            }, $this->currentFile);
        }
        catch (Exception $e)
        {
            // TODO: add a method to log errors.
        }

        // Display the final page and return the object, for chaining.
        echo($html);
        return $this;
    }

    /**
    *   @function private loadLangFile
    *   @param string $lang - Country code of the lang file wanted.
    *   @return string
    **/
    private function loadLangFile ($lang)
    {
        $lang = htmlentities(stripslashes($lang));
    	$filePath = $this->langsPath.'lang-'.$lang.'.'.$this->langsExt;
    	$fallbackPath = $this->langsPath.'lang-'.$this->defaultLang.'.'.$this->langsExt;

    	if (!in_array($filePath, $this->availablesLangs))
            return file_get_contents($fallbackPath);
    		//throw new Exception("Lang file <pre>$filePath</pre> doesn't exists.", 1);

        return file_get_contents($filePath);
    }

    /**
    *   @function private loadPageFile
    *   @param string $page - Path of the file to load.
    *   @return string
    **/
    private function loadPageFile ($page)
    {
    	if (!file_exists($page))
    		throw new Exception("Page <pre>$page</pre> doesn't exists.", 1);

        return file_get_contents($page);
    }
}
