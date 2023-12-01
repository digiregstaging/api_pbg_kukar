<?php

namespace App\Helpers;

use Exception;

class Upload
{

    static $path = "";
    static $base_64 = "";
    static $file_name = "";
    static $ext = "";

    public function __construct()
    {
    }

    public static function setPath(string $path)
    {
        self::$path = $path;
        return (new self);
    }

    public static function setFileName(string $file_name)
    {
        self::$file_name = $file_name;
        return (new self);
    }

    public static function setExtention(string $ext)
    {
        self::$ext = $ext;
        return (new self);
    }

    public static function setBase64(string $base_64)
    {
        self::$base_64 = $base_64;
        return (new self);
    }

    public static function saveImage()
    {
        log_message("info", "start method saveImage on Upload Helper");
        $image_parts = explode(";base64,", self::$base_64);
        if (!isset($image_parts[0])) {
            throw new Exception("invalid input image");
        }
        $image_type_aux = explode("image/", $image_parts[0]);
        if (!isset($image_type_aux[1])) {
            throw new Exception("file must be image");
        }
        $image_type = $image_type_aux[1];
        if (!empty(self::$ext)) {
            $image_type = self::$ext;
        }
        $image_base64 = base64_decode($image_parts[1]);
        $file = self::$path . self::$file_name . '.' . $image_type;
        $file_name2 = getenv("app.baseURL") . "/" . $file;

        file_put_contents($file, $image_base64);
        log_message("info", "end method saveImage on Upload Helper");
        return $file_name2 . "#" . time();
    }

    public static function savePdf()
    {
        log_message("info", "start method savePdf on Upload Helper");
        $pdf_parts = explode(";base64,", self::$base_64);
        if (!isset($pdf_parts[0])) {
            throw new Exception("invalid input pdf");
        }
        $pdf_type_aux = explode("application/", $pdf_parts[0]);
        if (!isset($pdf_type_aux[1])) {
            throw new Exception("file must be pdf");
        }
        $pdf_type = $pdf_type_aux[1];
        if (!empty(self::$ext)) {
            $pdf_type = self::$ext;
        }
        $pdf_base64 = base64_decode($pdf_parts[1]);
        $file = self::$path . self::$file_name . '.' . $pdf_type;
        $file_name2 = getenv("app.baseURL") . "/" . $file;

        file_put_contents($file, $pdf_base64);
        log_message("info", "end method savePdf on Upload Helper");
        return $file_name2 . "#" . time();
    }
}
