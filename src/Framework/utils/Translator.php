<?php

/**
* This class make possible to create applications that supports multilanguage.
*
* $localesDir               contains the path, from the app root folder that contains the translations
* $defaultLanguage          contains the default language string
* $selected_language        contains the language selected by the user
* $parameterEnclosingChars  makes possible to define pattern for look in to the templates
*
* User need to check the language accepted by the browser using: $_SERVER['HTTP_ACCEPT_LANGUAGE']
*
* Example of usages:
* 
* $t = new Translator('gsr', 'it');
* echo $t->of('presentation');
* echo $t->of('user.login.message', array('name' => 'Fabio', 'email' => 'Mattei'));
*
* Example of language file
*
* return [
*    'presentation' => 'Hello world',
*    'user.login.message' => 'Hello {name} {email}',
*    ];
*/
class Translator {

	/**
     * Directory path of the locale files.
     * @type string
     */
    protected $localesDir = 'locales';

    /**
     * The default language to use if the client language or the forced language locales are not found.
     * @type string
     */
    protected $defaultLanguage = 'en';

    /**
     * The current language being used to translate.
     * @type string
     */
    protected $selected_language = '';

    /**
     * Definition of the enclosing characters for the parameters inside the translation strings.
     * @type array
     */
    protected $parameterEnclosingChars = array('{', '}');

	/**
	 * Constructor of the class Translator
	 * Call it as:
	 *   new Translator( 'it' );
	 *   
	 * it will check if the defined language is on the list.
	 * if it is not it will fallback on the $fallback variable
	 *  
	 * 
	 * @param [type] $locale [description]
	 */
	function __construct( $chapter, $locale = '' ) {
		if ( $locale === '' ) {
			$client_language = $this->getClientLanguage();
			$this->selected_language = ( $client_language == null ? $this->defaultLanguage : $client_language );
		} else {
			$this->selected_language = $locale;
		}

		$path_to_file = $this->localesDir . '/' . $this->selected_language . '/' . $chapter . '.php';

		if ( !file_exists( $path_to_file ) ) {
            throw new Exception('Language locale not found for language '.$this->selected_language.' chapter '.$chapter.' !');
        }

		// include the file containing the string tranlations
		$this->loadedLocales[$this->selected_language] = require( $path_to_file );
	}

	/**
     * Returns the translation of a specific key from the current language locale.
     * Optionally you can fill the parameters array with the corresponding string replacements.
     * @param string $localeKey
     * @param array $parameters
     * @return string|null
     * @throws Exception
     */
    public function of( $localeKey, $parameters = array() ) {
        if ( is_string($localeKey) && !empty( $this->loadedLocales[$this->selected_language][$localeKey] ) ) {
            $text = $this->loadedLocales[$this->selected_language][$localeKey];
            if (!empty($parameters) && is_array($parameters)) {
                foreach ($parameters as $parameter => $replacement) {
                    $text = str_replace($this->parameterEnclosingChars[0] . $parameter . $this->parameterEnclosingChars[1], $replacement, $text);
                }
            }
            return $text;
        }
        return '';
    }


	/**
     * Returns the client language code.
     * @return string|null Returns the ISO-639 Language Code followed by ISO-3166 Country Code, like 'en-US'. Null if PHP couldn't detect it.
     */
    protected function getClientLanguage() {
        if ( isset( $_SESSION['HTTP_ACCEPT_LANGUAGE'] ) ) {
            return substr($_SESSION['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        } else if ( !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ) {
            return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        } else {
            return $this->defaultLanguage;
        }
    }

}
