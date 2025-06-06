<?php

namespace Fabiom\UglyDuckling\Framework\Utils;

/** Controller code
$file = uploadFile('file', true, true);
if (is_array($file['error'])) {
$message = '';
foreach ($file['error'] as $msg) {
$message .= '<p>'.$msg.'</p>';
}
} else {
$message = "File uploaded successfully ".$file['filepath'].$file['filename'];
}
echo $message;
 */

/** HTML Code
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
<input name="file" type="file" id="image" />
<input name="submit" type="submit" value="Upload" />
</form>
 */

class FileUpload {

    /**
     * @param $file_field
     * @param $check_image
     * @param $random_name
     * @param $path            //Set file upload path with trailing slash
     * @return array|null[]|void
     */
    static function uploadFile($file_field = null, $check_image = false, $random_name = false, $path = 'uploads/') {
        //The Validation
        // Create an array to hold any output
        $out = [];
        $out['error'] = [];

        if (!$file_field) {
            $out['error'][] = "Please specify a valid form field name";
        }

        if (!$path) {
            $out['error'][] = "Please specify a valid upload path";
        }

        if (count($out['error'])>0) {
            return $out;
        }

        //Make sure that there is a file
        if((!empty($_FILES[$file_field])) && ($_FILES[$file_field]['error'] == 0)) {

            // Get filename
            $file_info = pathinfo($_FILES[$file_field]['name']);
            $name = $file_info['filename'];
            $ext = $file_info['extension'];

            if (!is_uploaded_file($_FILES[$file_field]['tmp_name'])) {
                $out['error'][] = "File upload error";
            }

            //If $check image is set as true
            if ($check_image) {
                if (!getimagesize($_FILES[$file_field]['tmp_name'])) {
                    $out['error'][] = "Uploaded file is not a valid image";
                }
            }

            //Create full filename including path
            if ($random_name) {
                // Generate random filename
                $tmp = str_replace(['.',' '], ['',''], microtime());

                if (!$tmp || $tmp == '') {
                    $out['error'][] = "File must have a name";
                }
                $newname = $tmp.'.'.$ext;
            } else {
                $newname = $name.'.'.$ext;
            }

            //Check if file already exists on server
            if (file_exists($path.$newname)) {
                $out['error'][] = "A file with this name already exists";
            }

            if (count($out['error'])>0) {
                //The file has not correctly validated
                return $out;
            }

            if (move_uploaded_file($_FILES[$file_field]['tmp_name'], $path.$newname)) {
                //Success
                $out['filepath'] = $path;
                $out['filename'] = $newname;
                return $out;
            } else {
                $out['error'][] = "Server Error!";
                return $out;
            }

        } else {
            $out['error'][] = "No file uploaded";
            return $out;
        }
    }
}
