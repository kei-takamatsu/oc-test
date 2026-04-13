<?php
App::uses('AppHelper', 'View/Helper');
App::uses('Sanitize', 'Utility');

class ColorHelper extends AppHelper
{

    /**
     * 16進数のカラーを明るくする（暗くもできる）
     * @param int   $color
     * @param float $bright_per
     * @return int $color
     */
    public function bright($color, $bright_per)
    {
        if($bright_per > 1){
            $bright_per = 1;
        }
        if($bright_per < 0){
            $bright_per = 0;
        }
        $new_color    = array();
        $new_color[0] = hexdec(substr($color, 0, 2));
        $new_color[1] = hexdec(substr($color, 2, 2));
        $new_color[2] = hexdec(substr($color, 4, 2));
        $add          = round(255 * $bright_per);
        if((($new_color[0] + $new_color[1] + $new_color[2]) / 3) > (255 / 2)){
            $add *= -1;
        }
        foreach($new_color as $i => $c){
            $new_color[$i] = $c + $add;
            if($new_color[$i] < 0){
                $new_color[$i] = 0;
            }elseif($new_color[$i] > 255){
                $new_color[$i] = 255;
            }
            $new_color[$i] = sprintf('%02x', $new_color[$i]);
        }
        return implode($new_color);
    }

    /**
     * brightは明るいとbrightで逆に暗くなるようになっているが、これは明るくなる
     * @param $color
     * @param $bright_per
     * @return int $color
     */
    public function bright2($color, $bright_per)
    {
        if($bright_per > 1){
            $bright_per = 1;
        }
        if($bright_per < 0){
            $bright_per = 0;
        }
        $new_color    = array();
        $new_color[0] = hexdec(substr($color, 0, 2));
        $new_color[1] = hexdec(substr($color, 2, 2));
        $new_color[2] = hexdec(substr($color, 4, 2));
        $add          = round(255 * $bright_per);
        //if((($new_color[0] + $new_color[1] + $new_color[2]) / 3) > (255 / 2)) $add *= -1;
        foreach($new_color as $i => $c){
            $new_color[$i] = $c + $add;
            if($new_color[$i] < 0){
                $new_color[$i] = 0;
            }elseif($new_color[$i] > 255){
                $new_color[$i] = 255;
            }
            $new_color[$i] = sprintf('%02x', $new_color[$i]);
        }
        return implode($new_color);
    }

    /**
     * #ffffffを255,255,255にして返す関数
     */
    public function change_rgba($color, $alpha)
    {
        $new_color    = array();
        $new_color[0] = hexdec(substr($color, 0, 2));
        $new_color[1] = hexdec(substr($color, 2, 2));
        $new_color[2] = hexdec(substr($color, 4, 2));
        return 'rgba('.$new_color[0].','.$new_color[1].','.$new_color[2].','.(int)$alpha / 100 .')';
    }

}