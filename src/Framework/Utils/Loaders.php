<?php

namespace Fabiom\UglyDuckling\Framework\Utils;

/**
 * Load a block file.
 * It starts checking the "framework/blocks" folder in order to check that the passed
 * name matches a core block file name.
 * If it does not match it checks the "aggregators" folder looking for a file named:
 * aggregatos/$type/blocks/$path.php
 * If no file is found it checks the "chapter" folder looking for a file named:
 * chapter/$type/blocks/$path.php
 *
 * If no file is found the function writes an ERROR message in the log
 *
 * @param        string     chapter name or office name or 'core'
 * @param        string     path concatenated to file name
 *
 * @return       string     Just for testing purpose
 */
function block( $type, $path ) {

	if ( $type == '' OR $path == '' ) throw new \GeneralException('General malfuction!!!');

	if ( $type == 'template') {

		$filepath = 'templates/blocks/'.$path.'.php';

		if ( TESTMODE == 'on' ) return $filepath;

		require_once $filepath;

	} else {

		if ( file_exists( 'controllers/'.$type.'/blocks/'.$path.'.php' ) ) {
		    require_once 'controllers/'.$type.'/blocks/'.$path.'.php';
		}

		if ( file_exists( 'chapters/'.$type.'/blocks/'.$path.'.php' ) ) {
		    require_once 'chapters/'.$type.'/blocks/'.$path.'.php';
		}
	}
}

/**
 * Load an usecase file.
 * Usecase are associated to a posttype, in effect they all are contained
 * in a folder named "usecases" inside the postype folder.
 * If the usecase file is not found the systems writes an ERROR message in the log
 *
 * @param        string     chapter name
 * @param        string     path concatenated to file name
 *
 * @return       string     Just for testing purpose
 */
function usecase( $chapter, $path ) {

	if ( $chapter == '' OR $path == '' ) throw new \GeneralException('General malfuction!!!');

	$filepath = 'chapters/'.$chapter.'/usecases/'.$path.'.php';

	if ( TESTMODE == 'on' ) return $filepath;

	if ( file_exists( $filepath ) ) {

	    require_once $filepath;

	} else {

		if ( TESTMODE == 'on' ) return '';

		$logger = new Logger();
		$logger->write( 'ERROR: -usecase- file dose not exists: '.$filepath );

	}
}

/**
 * Load a DAO (data access object) file.
 * DAO's are associated to a posttype, in effect they all are contained
 * in a folder named "dao" inside the postype folder.
 * If the dao file is not found the systems writes an ERROR message in the log
 *
 * @param        string     chapter name
 * @param        string     path concatenated to file name
 *
 * @return       string     Just for testing purpose
 */
function dao( $chapter, $path ) {

	if ( $chapter == '' OR $path == '' ) throw new \GeneralException('General malfuction!!!');

	$filepath = 'chapters/'.$chapter.'/dao/'.$path.'.php';

	if ( TESTMODE == 'on' ) return $filepath;

	if ( APPTESTMODE == 'on' ) $filepath = 'test/fakedaos/'.$path.'.php';

	if ( file_exists( $filepath ) ) {

	    require_once $filepath;

	} else {

		if ( TESTMODE == 'on' ) return '';

		$logger = new Logger();
		$logger->write( 'ERROR: -dao- file dose not exists: '.$filepath );

	}
}

/**
 * Load a DAO (data access object) file.
 * DAO's are associated to a posttype, in effect they all are contained
 * in a folder named "dao" inside the postype folder.
 * If the dao file is not found the systems writes an ERROR message in the log
 *
 * @param        string     chapter name
 * @param        string     path concatenated to file name
 *
 * @return       new dao()  that can be used in the code and saves some line of code
 */
function dao_exp( $chapter, $path, $connection = '' ) {

 	if ( $chapter == '' OR $path == '' ) throw new \GeneralException('General malfuction!!!');

 	$filepath = 'chapters/'.$chapter.'/dao/'.strtolower($path).'.php';

 	if ( TESTMODE == 'on' ) return $filepath;

 	if ( APPTESTMODE == 'on' ) {
		$fakedaoname = $path.'Fake';
 		$filepath = 'test/fakedaos/'.$chapter.'/'.strtolower($fakedaoname).'.php';
 	}

 	if ( file_exists( $filepath ) ) {

 	    require_once $filepath;
		if ( isset( $fakedaoname ) )  {
			return new $fakedaoname();
		} else {
			$dao = new $path();
			if ( !is_string( $connection ) ) {
				$dao->setDBH( $connection );
			}
			return $dao;
		}

 	} else {

 		if ( TESTMODE == 'on' ) return '';

 		$logger = new Logger();
 		$logger->write( 'ERROR: -dao- file dose not exists: '.$filepath );

 	}
}

/**
 * Load an paperwork file.
 * Paperworks are associated to a posttype, in effect they all are contained
 * in a folder named "paperworks" inside the postype folder.
 * If the paperwork file is not found the systems writes an ERROR message in the log
 *
 * @param        string     chapter name
 * @param        string     path concatenated to file name
 *
 * @return       string     Just for testing purpose
 */
function paperwork( $chapter, $class_name ) {

	if ( $chapter == '' OR $class_name == '' ) throw new \GeneralException('General malfuction!!!');

	$filepath = 'custom/paperworks/'.$chapter.'/'.strtolower($class_name).'.php';

	if ( TESTMODE == 'on' ) return $filepath;

	if ( file_exists( $filepath ) ) {

		require_once $filepath;
		if (class_exists($class_name)) {
			return new $class_name();
		} else {
			require_once( 'framework/paperworks/basicpaper.php' );
			return new BasicPaper();
		}

	} else {

		require_once( 'framework/paperworks/basicpaper.php' );
		return new BasicPaper();

	}
}

/**
 * Load an paperflow file.
 * Paperflows tell us what is the class_name (flow) of the paper inside the system.
 * If the paperflow file is not found the systems writes an ERROR message in the log
 *
 * @param        string     chapter name
 * @param        string     class_name concatenated to file name
 *
 * @return       string     Just for testing purpose
 */
function paperflow( $chapter, $class_name ) {

	$filepath = 'custom/paperflows/'.$chapter.'/'.strtolower($class_name).'.php';

	if ( TESTMODE == 'on' ) return $filepath;

	if ( file_exists( $filepath ) ) {

		require_once $filepath;
		if (class_exists($class_name)) {
			return new $class_name();
		}else {
			require_once( 'framework/paperworks/basicflow.php' );
			return new BasicFlow();
		}

	} else {

		require_once( 'framework/paperworks/basicflow.php' );
		return new BasicFlow();

	}
}

/**
 * Load an helper file.
 * helpers are associated to a chapter, in effect they all are contained
 * in a folder named "helpers" inside the chapter folder.
 * If the helper file is not found the systems writes an ERROR message in the log
 *
 * @param        string     chapter name
 * @param        string     path concatenated to file name
 *
 * @return       string     Just for testing purpose
 */
function helper( $chapter, $path ) {

	if ( $chapter == '' OR $path == '' ) throw new \GeneralException('General malfuction!!!');

	$filepath = 'chapters/'.$chapter.'/helpers/'.$path.'.php';

	if ( TESTMODE == 'on' ) return $filepath;

	if ( file_exists( $filepath ) ) {

	    require_once $filepath;

	} else {

		if ( TESTMODE == 'on' ) return '';

		$logger = new Logger();
		$logger->write( 'ERROR: -helper- file dose not exists: '.$filepath );

	}
}

/**
 * Load an partial file.
 * partials are associated to a posttype, in effect they all are contained
 * in a folder named "partial" inside the postype folder.
 * If the partial file is not found the systems writes an ERROR message in the log
 *
 * @param        string     chapter name
 * @param        string     path concatenated to file name
 *
 * @return       string     Just for testing purpose
 */
function partial( $chapter, $path ) {

	if ( $chapter == '' OR $path == '' ) throw new \GeneralException('General malfuction!!!');

	$filepath = 'chapters/'.$chapter.'/partial/'.$path.'.php';

	if ( TESTMODE == 'on' ) return $filepath;

	if ( file_exists( $filepath ) ) {

	    require_once $filepath;

	} else {

		if ( TESTMODE == 'on' ) return '';

		$logger = new Logger();
		$logger->write( 'ERROR: -partial- file dose not exists: '.$filepath );

	}
}

/**
 * Load an importer file.
 * importers are associated to a chapter, in effect they all are contained
 * in a folder named "importers" inside the chapter folder.
 * If the importer file is not found the systems writes an ERROR message in the log
 *
 * @param        string     chapter name
 * @param        string     path concatenated to file name
 *
 * @return       string     Just for testing purpose
 */
function importer( $chapter, $path ) {

	if ( $chapter == '' OR $path == '' ) throw new \GeneralException('General malfuction!!!');

	$filepath = 'chapters/'.$chapter.'/importers/'.$path.'.php';

	if ( TESTMODE == 'on' ) return $filepath;

	if ( file_exists( $filepath ) ) {

	    require_once $filepath;

	} else {

		if ( TESTMODE == 'on' ) return '';

		$logger = new Logger();
		$logger->write( 'ERROR: -importer- file dose not exists: '.$filepath );

	}
}

/**
 * Load an exporter file.
 * exporters are associated to a chapter, in effect they all are contained
 * in a folder named "exporters" inside the chapter folder.
 * If the exporter file is not found the systems writes an ERROR message in the log
 *
 * @param        string     chapter name
 * @param        string     path concatenated to file name
 *
 * @return       string     Just for testing purpose
 */
function exporter( $chapter, $path ) {

	if ( $chapter == '' OR $path == '' ) throw new \GeneralException('General malfuction!!!');

	$filepath = 'chapters/'.$chapter.'/exporters/'.$path.'.php';

	if ( TESTMODE == 'on' ) return $filepath;

	if ( file_exists( $filepath ) ) {

	    require_once $filepath;

	} else {

		if ( TESTMODE == 'on' ) return '';

		$logger = new Logger();
		$logger->write( 'ERROR: -exporters- file dose not exists: '.$filepath );

	}
}

/**
 * Load an exporter file.
 * exporters are associated to a chapter, in effect they all are contained
 * in a folder named "exporters" inside the chapter folder.
 * If the exporter file is not found the systems writes an ERROR message in the log
 *
 * @param        string     chapter name
 * @param        string     path concatenated to file name
 *
 * @return       string     Just for testing purpose
 */
function model( $chapter, $path ) {

	if ( $chapter == '' OR $path == '' ) throw new \GeneralException('General malfuction!!!');

	$filepath = 'chapters/'.$chapter.'/model/'.$path.'.php';

	if ( TESTMODE == 'on' ) return $filepath;

	if ( file_exists( $filepath ) ) {

	    require_once $filepath;

	} else {

		if ( TESTMODE == 'on' ) return '';

		$logger = new Logger();
		$logger->write( 'ERROR: -model- file dose not exist: '.$filepath );

	}
}

/**
 * Load a file containing page functions
 *
 * @param        string     page folder name
 * @param        string     page file name
 *
 * @return       string     Just for testing purpose
 */
function page( $page_folder, $page_file ) {

	if ( $page_folder == '' OR $page_file == '' ) throw new \GeneralException('General malfuction!!!');

	$filepath = 'pages/'.$page_folder.'/'.$page_file.'.php';

	if ( TESTMODE == 'on' ) return $filepath;

	if ( file_exists( $filepath ) ) {

	    require_once $filepath;

	} else {

		if ( TESTMODE == 'on' ) return '';

		$logger = new Logger();
		$logger->write( 'ERROR: -page- file dose not exists: '.$filepath );

	}
}

/**
 * Load a library file.
 * Libraries are contained in the folder named "framework"
 *
 * If no lib file is found the function writes an ERROR message in the log
 *
 * @param        string     lib file name
 *
 * @return       string     Just for testing purpose
 */
function lib( $path ) {

	if ( $path == '' ) throw new \GeneralException('General malfuction!!!');

	if ( file_exists( 'framework/libs/'.$path.'.php' ) ) {

		if ( TESTMODE == 'on' ) return 'framework/libs/'.$path.'.php';

		require_once 'framework/libs/'.$path.'.php';

	} elseif ( file_exists( 'libs/'.$path.'.php' ) ) {

		require_once 'libs/'.$path.'.php';

	} else {

		if ( TESTMODE == 'on' ) return '';

		$logger = new Logger();
		$logger->write( 'ERROR: -library- file dose not exists: '.$path );

	}
}

/**
 * Load an utils file. (using require_once)
 * Uitls files are libraries of useful functions you can use inside the code.
 * Some utils are provided by the framework, they are located in /framework/utils.
 * User can write his own utils and put the in the project root folder: /utils
 * When user uses the utils function in order to load a file the function checks
 * initially in the framwork/utils folder and then in /utils folder.
 *
 * If no file is found the function writes an ERROR message in the log
 *
 * @param        string     utils file name
 *
 * @return       string     Just for testing purpose
 */
function utils( $path ) {
	if ( $path == '' ) throw new \GeneralException('General malfuction!!!');

	if ( file_exists( 'framework/utils/'.$path.'.php' ) ) {

		if ( TESTMODE == 'on' ) return 'framework/utils/'.$path.'.php';

		require_once 'framework/utils/'.$path.'.php';

	} elseif ( file_exists( 'utils/'.$path.'.php' ) ) {

		require_once 'utils/'.$path.'.php';

	} else {

		if ( TESTMODE == 'on' ) return '';

		$logger = new Logger();
		$logger->write( 'ERROR: -utils- file dose not exists: ' . $path );

	}
}

/**
 * Load a controller file. (using require_once)
 * Controllers are contained in the folder named "controllers".
 * They load data from database in order to populate blocks,
 * they load the blocks and they organize blocks
 * so blocks can be displayed in the template.
 *
 * @param        string     Group
 * @param        string     Action
 * @param        string     Parameters: string containing all parameters separated by '/'
 * @param        string     If set to "on" make the function return the calculated path
 *                          instead to "require" the file.
 * @return       string     Just for testing purpose
 */
function controller( $team, $chapter, $action = 'index' ) {
	
	$filepath1 = 'controllers/'.$team.'/'.$chapter.'/'.$action.'.php';
	
	if ( file_exists( $filepath1 ) ) {
		require_once $filepath1;
		$class_name = ucfirst($team).'_'.ucfirst($chapter).'_'.ucfirst($action);
		return new $class_name;
	}
	
	$filepath2 = 'chapters/'.$chapter.'/controllers/'.$action.'.php';
	
	if ( file_exists( $filepath2 ) ) {
		require_once $filepath2;
		$class_name = ucfirst($chapter).'_'.ucfirst($action);
		return new $class_name;
	}
	
	$logger = new Logger();
	$logger->write( 'ERROR: -controller- does not exists: ' . $team . '-' . $chapter . '-' . $action );
}

function private_aggregator() {
	if ( !class_exists( 'PrivateController' ) ) {
		require_once 'framework/aggregators/privateaggregator.php';
	}
}

function public_aggregator() {
	if ( !class_exists( 'PublicController' ) ) {
		require_once 'framework/aggregators/publicaggregator.php';
	}
}

/**
 * It creates a URL appending the content of variable $_SESSION['office'] to BASEPATH
 *
 * Result is: BASEPATH . $_SESSION['office'] . $final_part
 *
 * @param        string     Chapter
 * @param        string     Action
 * @param        string     Parameters: string containing all parameters separated by '/'
 * @param        string     Extension:  .html by default
 *
 * @return       string     The url well formed
 */
function make_url( $chapter = 'main', $action = '', $parameters = '', $extension = '.html' ) {

	if ( $chapter == 'main' AND $action == '' ) {
		return BASEPATH;
	}
	if ( $chapter != 'main' AND $action == '' ) {
		return BASEPATH.$_SESSION['office'].'-'.$chapter.'/index.html';
	}
    if ( $chapter == 'main' ) {
        return BASEPATH.$_SESSION['office'].'/'.$action.( $parameters == '' ? '' : '/'.$parameters ).$extension;
    } else {
        return BASEPATH.$_SESSION['office'].'-'.$chapter.'/'.$action.( $parameters == '' ? '' : '/'.$parameters ).$extension;
    }

}

/**
 * It creates a URL all depending from parameters
 * It is not going to use $_SESSION['office'] variable
 *
 * Result is: BASEPATH . $final_part
 *
 * @param        string     Office
 * @param        string     Chapter
 * @param        string     Action
 * @param        string     Parameters: string containing all parameters separated by '/'
 * @param        string     Extension:  .html by default
 *
 * @return       string     The url well formed
 */
function make_complete_url( $team = 'public', $chapter = 'main', $action = '', $parameters = '', $extension = '.html' ) {

    if ( $chapter == 'main' AND $action == '' ) {
        return BASEPATH;
    }
    if ( $chapter != 'main' AND $action == '' ) {
        return BASEPATH.$team.'-'.$chapter.'/index.html';
    }
    if ( $chapter == 'main' ) {
        return BASEPATH.$team.'/'.$action.( $parameters == '' ? '' : '/'.$parameters ).$extension;
    } else {
        return BASEPATH.$team.'-'.$chapter.'/'.$action.( $parameters == '' ? '' : '/'.$parameters ).$extension;
    }

}

/**
 * It creates a link using the make_url function
 *
 * Result is: BASEPATH . $_SESSION['office'] . $final_part
 *
 * @param        string     Text for link
 * @param        string     Chapter
 * @param        string     Action
 * @param        string     Parameters: string containing all parameters separated by '/'
 * @param        string     Extension:  .html by default
 *
 * @return       string     The url well formed
 */
function make_link( $text, $chapter = 'main', $action = '', $args = '' ) {
	// default values
	$class_string = '';
	$parameters_string = '';
	$extension_string = '.html';
	$onclick_string = '';
	$data_original_title = '';

	// checking if args array contains any value that override the default value
	if ( is_array( $args ) ) {
		if ( isset( $args['class'] ) ) {
			$class_string = ' class="'.$args['class'].'" ';
		}
		if ( isset( $args['parameters'] ) ) {
			$parameters_string = $args['parameters'];
		}
		if ( isset( $args['onclick'] ) ) {
			$onclick_string = 'onclick="return confirm(\''.$args['onclick'].'\')"';
		}
		
		if ( isset( $args['title'] ) ) {
			$data_original_title = 'data-original-title="'.$args['title'].'"';
		}
	}

	return '<a href="'.make_url($chapter, $action, $parameters_string, $extension_string).'" data-toggle="tooltip" '.$class_string.' '.$onclick_string.' '.$data_original_title.'>'.$text.'</a>';
}
