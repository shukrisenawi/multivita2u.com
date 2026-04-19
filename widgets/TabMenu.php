<?php

namespace app\widgets;

use yii\helpers\Url;

class TabMenu extends \yii\bootstrap\Widget {

    public $menu;
    public $select;

    public function run() {
        $echo = null;
        if ($this->menu) {
            $echo = '<ul>';
            foreach ($this->menu as $key => $value) {
                //  $echo.= '<li' . ($this->select == $key ? ' class="active"' : '') . '><a href="' . Url::to($value[1]) . '">' . $value[0] . '</a></li>';
            }
            $echo.= '</ul>';
        }
        return $echo;
    }

}
