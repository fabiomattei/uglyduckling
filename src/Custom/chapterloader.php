<?php
	
/**
* This class helps to load a paper family file, it is basically a paper family index
*/

namespace custom;

use custom\chapters\expense\ExpenseChapter;

class ChapterLoader {

	static function load_chapter( $chapter_slug ) {
		if ( $chapter_slug == 'expense' ) {
			return new ExpenseChapter();
		}
		if ( $chapter_slug == 'course' ) {
			require_once( 'custom/chapters/course/coursechapter.php' );
			return new CourseChapter();
		}
 		$logger = new Logger();
 		$logger->write( 'ERROR: - ChapterLoader::load_chapter - failed. ' );
		throw new \Exception('ChapterLoader::load_chapter - failed!!!');
	}
	
} 
