<?php


namespace App\Service\Utils;


class System
{
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
    function byte2Size($bytes, $roundLength=1)
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
                return $this->byte2Size($bytes, $roundLength);
            }
        }
    }
}