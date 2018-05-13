<?php
	
/**
* This class helps to load a paper family file, it is basically a paper family index
*/

use custom\chapters\expense\ExpenseChapter;

class AssetLoader {

	static function load_asset( $asset_slug ) {
		if ( $asset_slug == 'expense' ) {
			return new ExpenseChapter();
		}
		if ( $asset_slug == 'course' ) {
			require_once( 'custom/chapters/course/coursechapter.php' );
			return new CourseChapter();
		}
 		$logger = new Logger();
 		$logger->write( 'ERROR: - ChapterLoader::load_chapter - failed. ' );
		throw new \Exception('ChapterLoader::load_chapter - failed!!!');
	}
	
} 
