<?php
/**
 * V 0.3
 * kubio
 */

namespace App\Service;


use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

class Utils
{
    private $emailValidator = null;

    /**
     * Formats a string date to a especific format
     * @param string $date_string
     * @param string $format_source
     * @param string $format_output
     * @return string
     */
    public function dateFormat($date_string, $format_source = 'Y-m-d H:i:s', $format_output = 'Y-m-d')
    {
        $myDateTime = \DateTime::createFromFormat($format_source, $date_string);
        return $myDateTime->format($format_output);
    }

    /**
     * Formats date right now to a especific format
     * @param string $format_output
     * @return string
     * @throws \Exception
     */
    public function dateFormatToday($format_output = 'Y-m-d')
    {
        $date = new \DateTime();
        $format_source = 'Y-m-d H:i:s';
        $date_string = $date->format($format_source);
        return $this->dateFormat($date_string, $format_source, $format_output);
    }

    public function dateFormatStrftime($date_string, $format_output = '%e de %B del %Y', $format_source = 'Y-m-d H:i:s')
    {
        $date = \DateTime::createFromFormat($format_source, $date_string);
        return  strftime($format_output,$date->getTimestamp());
    }

    public function meses($mes_index = false)
    {

        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        return $mes_index !== false ? $meses[$mes_index] : $meses;
    }

    /**
     * Numeros formateado en MySQL para ser sumados adecuadamente
     * @return string
     */
    function format_to_int()
    {
        $value = 0;
        foreach(func_get_args() as $arg){
            $value += (float)str_replace(',', '', $arg);
        }
        return number_format($value, 0);
    }

    /**
     * TODO : agregar a biblioteca
     * Version 1.0
     * @param $val
     * @return int|mixed|string
     */
    function return_bytes($val) 
    {
        $val = trim($val);
        $last = $val[strlen($val)-1];
        $val = str_replace($last, '', $val);
        switch(strtolower($last))
        {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }

    function max_file_upload_in_bytes() 
    {
        //select maximum upload size

        $max_upload = $this->return_bytes(ini_get('upload_max_filesize'));
        //select post limit
        $max_post = $this->return_bytes(ini_get('post_max_size'));
        //select memory limit
        $memory_limit = $this->return_bytes(ini_get('memory_limit'));
        // return the smallest of them, this defines the real limit
        return min($max_upload, $max_post, $memory_limit);
    }

    /**
     * @param $bytes
     * @param int $roundLength
     * @return string
     */
    function byte_2_size($bytes, $roundLength=1) 
    {
        $kb = 1024;         // Kilobyte
        $mb = 1024 * $kb;   // Megabyte
        $gb = 1024 * $mb;   // Gigabyte
        $tb = 1024 * $gb;   // Terabyte

        if($bytes < $kb) {
            if(!$bytes){
                $bytes = '0';
            }
            return (($bytes + 1)-1).' B';
        } else if($bytes < $mb) {
            return round($bytes/$kb,$roundLength).' KB';
        } else if($bytes < $gb) {
            return round($bytes/$mb,$roundLength).' MB';
        } else if($bytes < $tb) {
            return round($bytes/$gb,$roundLength).' GB';
        } else {
            return round($bytes/$tb,$roundLength).' TB';
        }
    }

    /**
     * @param $bytes
     * @param int $roundLength
     * @param string $size [
     *  'KB', 'MB', 'GB', 'TB'
     * ]
     * @return float|string
     */
    function byteToSize($bytes, $roundLength = 1, $size = null)
    {
        $kb = 1024;         // Kilobyte
        $mb = 1024 * $kb;   // Megabyte
        $gb = 1024 * $mb;   // Gigabyte
        $tb = 1024 * $gb;   // Terabyte
        
        if($bytes > 0) {
            if($size) {
                switch ($size) {
                    case 'KB':
                        return round($bytes / $kb, $roundLength);
                    case 'MB':
                        return round($bytes / $mb, $roundLength);
                    case 'GB':
                        return round($bytes / $gb, $roundLength);
                    case 'TB':
                        return round($bytes / $tb,$roundLength);
                    default:
                        return $bytes;
                }
            } else {
                return $this->byte_2_size($bytes, $roundLength);
            }
        }
    }
    


    /**
     * Convierte string camelCase a camel_case
     * @param $input
     * @param string $glue
     * @return string
     */
    function fromCamelCase($input, $glue = "_") {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode($glue, $ret);
    }

    /**
     * Convierte string convertir_camel a convertirCamel
     * @param $string
     * @param string $separator
     * @return mixed
     * @deprecated use convertToCamelCase
     */
    function toCamelCase($string, $separator = "_")
    {
        // Remove underscores, capitalize words, squash, lowercase first.
        return lcfirst(str_replace(' ', '', ucwords(str_replace($separator, ' ', $string))));
    }

    /**
     * Convierte string convertir_camel a convertirCamel
     * @param $string
     * @param string $separator
     * @return mixed
     */
    public function convertToCamelCase($string, $separator = "_")
    {
        return str_replace(' ', '', mb_convert_case(str_replace($separator, ' ', $string), MB_CASE_TITLE));
    }

    /**
     * ucfirst() function for multibyte character encodings
     * https://stackoverflow.com/questions/2517947/ucfirst-function-for-multibyte-character-encodings
     * @param $string
     * @param $encoding
     * @return string
     */
    function mbUcfirst($string, $encoding)
    {
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        dump(mb_detect_encoding($string));
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        $x = mb_strtoupper($firstChar, $encoding) . $then;
        return $x;
    }

    /**
     * @param $input
     * @param string $glue
     * @return mixed
     * @deprecated
     */
    function from_camel_case($input, $glue = "_") {
        return $this->fromCamelCase($input, $glue);
    }

    function getScheme()
    {
        if ( (! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') || (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ) {
            $server_request_scheme = 'https';
        } else {
            $server_request_scheme = 'http';
        }
        return $server_request_scheme;
    }

    /**
     * Given a full path, if the file exists change name until is unique adding a number appendix
     * @param string $target_file full path of the file
     * @return string
     */
    public function findUniqueFileName($target_file)
    {
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

        $appendix = 1;
        while(file_exists($target_file)){
            $search = $appendix == 1 ? ".$file_type" : "-" . ($appendix - 1) . ".$file_type";
            $target_file = str_replace($search, "-$appendix.$file_type", $target_file);
            $appendix++;
        }
        return $target_file;
    }

    /**
     * Moves a file uploaded via form to the especified location
     * @param $input_name
     * @param $location_dir
     * @param bool $custom_file_name
     * @param array $allowed_formats,
     * @param bool|int $max_upload_size
     * @param int $concurrence_action
     * @return string
     * @throws \Exception
     */
    public function uploadFile($input_name, $location_dir, $custom_file_name = false, $allowed_formats = [],
                               $max_upload_size = false, $concurrence_action = 1)
    {
        $location_file_uploaded = "";

        if(isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] != 4 && $_FILES[$input_name]['size'] > 0) {
            $file_name = basename($_FILES[$input_name]["name"]);
            $target_file = $location_dir . "/" . $file_name;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
            
            if($custom_file_name)
                $target_file = str_replace("$file_name.$file_type", "$custom_file_name.$file_type", $target_file);

            if($concurrence_action == 0 && file_exists($target_file))
                throw new \Exception("Archivo ya existe");
            else if($concurrence_action == 1)
                //if exists, add appendix to file name
                $target_file = $this->findUniqueFileName($target_file);
            

            // Check file size
            // TODO: if false, must checks with system capacity
            $max_upload_size = $max_upload_size ? $max_upload_size : 2100000;
            if ($_FILES[$input_name]["size"] > $max_upload_size)
                throw new \Exception("Archivo de tamaño muy grande. Tamaño maximo : " . $this->byte_2_size($max_upload_size));

            // Allow certain file formats
            if (count($allowed_formats) && !in_array($file_type, $allowed_formats))
                throw new \Exception("Formato $file_type, no soportado");
            if (!move_uploaded_file($_FILES[$input_name]["tmp_name"], $target_file))
                throw new \Exception("Archivo no pudo cargarse.");

            $location_file_uploaded = $target_file;
        }
        return str_replace($location_dir . '/', '', $location_file_uploaded);
    }

    /**
     * Download a file from server
     * @param string $file_path the file to download
     */
    public function downloadFile($file_path)
    {
        $path_parts = pathinfo($file_path);
        $file_name  = $path_parts['basename'];
        $file_ext   = $path_parts['extension'];

        // set the mime type based on extension, add yours if needed.
        $ctype_default = "application/octet-stream";
        $content_types = array(
            "exe" => "application/octet-stream",
            "zip" => "application/zip",
            "mp3" => "audio/mpeg",
            "mpg" => "video/mpeg",
            "avi" => "video/x-msvideo",
            "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
        );
        $ctype = isset($content_types[$file_ext]) ? $content_types[$file_ext] : $ctype_default;
        header("Content-Type: " . $ctype);
        header("Content-Length: " . filesize($file_path));
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        ob_clean();
        flush();
        readfile($file_path);
        exit;
    }

    public function emailIsValid($email)
    {
        if(!$this->emailValidator) {
            $this->emailValidator = new EmailValidator();
        }
        return $this->emailValidator->isValid($email, new RFCValidation());
    }
}