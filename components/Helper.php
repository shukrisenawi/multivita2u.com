<?php

namespace app\components;

use app\models\Settings;

class Helper
{
    static public function convertDateFormat($date, $newDate = "Y-m-d")
    {
        return date($newDate, strtotime($date));
    }

    static public function viewDate($date, $format = "d-m-Y")
    {
        if ($date) {
            $value = strtotime($date);
            return date($format, $value);
        } else {
            return null;
        }
    }

    static public function convertTimeFormat($time, $newDate = "H:i:s")
    {
        return date($newDate, strtotime($time));
    }

    static public function viewTime($time, $format = "h:i A")
    {
        if ($time) {
            $value = strtotime($time);
            return date($format, $value);
        } else {
            return null;
        }
    }

    static public function arrayToFilter($array, $column = "id", $operation = "OR")
    {
        return "(" . $column . "='" . implode("' " . $operation . " " . $column . "='", $array) . "')";
    }

    static public function convertText($message, $data = [])
    {
        $var = [];
        foreach ($data as $key => $value) {
            $var[("{" . $key . "}")] = $value;
        }
        return strtr($message, $var);
    }

    public static function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 100)
    {
        $quality = 10;
        $imgsize = getimagesize($source_file);
        $width = $imgsize[0];
        $height = $imgsize[1];
        $mime = $imgsize['mime'];

        switch ($mime) {
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
                break;

            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 9;
                break;

            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 100;
                break;



            default:
                return false;
                break;
        }

        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $src_img = $image_create($source_file);

        $width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;
        //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
        if ($width_new > $width) {
            //cut point by height
            $h_point = (($height - $height_new) / 2);
            //copy image
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        } else {
            //cut point by width
            $w_point = (($width - $width_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }

        $image($dst_img, $dst_dir, $quality);

        if ($dst_img)
            imagedestroy($dst_img);
        if ($src_img)
            imagedestroy($src_img);
    }
    public static function convertMoney($value)
    {
        $setting = Settings::value();
        return $setting['currency'] . $value;
    }

    public static function dateToOri($date, $time = true)
    {
        $str = '';
        if ($date) {
            $dateOk = explode(' ', $date);
            if ($dateOk[0]) {
                $dateExplode = explode('-', $dateOk[0]);
                $str = $dateExplode[2] . '-' . $dateExplode[1] . '-' . $dateExplode[0];
            }
            if ($time) {
                if (isset($dateOk[1])) {
                    // $time = explode(":", $dateOk[1]);
                } else {
                    $str .= ' 00:00:00';
                }
            }
        }
        return $str;
    }

    public static function cleanId($id)
    {
        $id = str_replace("%5Boid%5D=", "", $id);
        return $id;
    }
}
