<?php
namespace App\Utils;

class ImageToBoxShadow {
    public $imageOriginalBody;
    public $quality;
    function __construct() {

    }

    public function addImageContent($body)
    {
        $this->imageOriginalBody = $body;
        return true;
    }
    private function getImageString() 
    {
        $imageString = imagecreatefromstring($this->imageOriginalBody);
        return ($imageString !== false) ? $imageString : false;
    }


    public function setQuality($quality)
    {
        if($quality > 0 and $quality < 35) {
            $this->quality = $quality;
        }
    }
    public  function generate()
    {
        $BoxShadowCSS = 'box-shadow: ';
        $PixelArray = [];
        $ImageString = $this->getImageString();
        if($ImageString !== false) {
            
            $w = imagesx($ImageString);
            $h = imagesy($ImageString);
            for($i = 0; $i < $w; $i = $i + $this->quality) {
                for($j = 0; $j < $h; $j = $j + $this->quality) {
                    $color_index = imagecolorat($ImageString, $i, $j);
                    // make it human readable
                    $PixelArray[$i][$j] = imagecolorsforindex($ImageString, $color_index);
                    if($i > 0 || $j > 0 ) {
                        $BoxShadowCSS .= ',';
                    }
                    $BoxShadowCSS .= " {$i}px {$j}px rgb(".$PixelArray[$i][$j]['red'] .",". $PixelArray[$i][$j]['green'] .",". $PixelArray[$i][$j]['blue'] .")";
                    unset($PixelArray[$i][$j]);
                }
            }

            $BoxShadowCSS .= ';';
            return [
                'height' => $h,
                'width' => $w,
                'boxShadow' => $BoxShadowCSS,
                'quality' => $this->quality,
            ];
        }
    }


}