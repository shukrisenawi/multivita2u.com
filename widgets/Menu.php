<?php

namespace app\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use app\components\Helper;

class Menu extends \yii\bootstrap\Widget
{

    public $idPage;
    public $subMenu;
    public $subBtn;
    public $select;

    public function access()
    {
        return [
            [
                'actions' => ['*'],
                'allow' => true,
                'roles' => ['@'],
            ]
        ];
    }

    private function listMenu()
    {
        $_user = Yii::$app->user->getIdentity();

        $menuGuest = [['label' => 'Login', 'url' => ['/site/login']]];
        $menuMember = ['label' => (!Yii::$app->user->isGuest ? ('<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>') : ""), 'url' => ['/site/logout'], 'hidden' => true];

        if ($_user && $_user->isAdmin()) {
            $menuNav = [
                ['icon' => 'fa fa-home', 'label' => 'Dashboard', 'url' => ['/dashboard/admin']],
                ['icon' => 'fa fa-users', 'label' => 'Top Stockist', 'url' => ['/stockist/index']],
                ['icon' => 'fa fa-users', 'label' => 'Members', 'url' => ['/user/index']],
                ['icon' => 'fa fa-cart-arrow-down', 'label' => 'Buy', 'url' => ['/buy/index']],
                ['icon' => 'fa fa-network-wired', 'label' => 'Network', 'url' => ['/network/index']],
                ['icon' => 'fa fa-exchange-alt', 'label' => 'Transaction', 'url' => ['/transaction/index']],
                ['icon' => 'fas fa-list', 'label' => 'Pin Wallet Transaction', 'url' => ['/transaction-pin-wallet/index']],
                ['icon' => 'fa fa-hand-holding-usd', 'label' => 'Withdrawal', 'url' => ['/withdrawal/index']],
                ['icon' => 'fa fa-money', 'label' => 'Point Payment', 'url' => ['/point-payment/index']],
                ['icon' => 'fa fa-usd', 'label' => 'Point Balance', 'url' => ['/point-balance/index']],
                ['icon' => 'fa fa-newspaper', 'label' => 'News', 'url' => ['/news/index']],
                ['icon' => 'fa fa-portrait', 'label' => 'Profile', 'url' => ['/profile/index'], 'hidden' => true],
                ['icon' => 'fa fa-cog', 'label' => 'Settings', 'url' => ['/settings/index'], 'hidden' => true],
            ];
            array_push($menuNav, $menuMember);
        } else if ($_user && $_user->isStockistState()) {
            $menuNav = [
                ['icon' => 'fa fa-home', 'label' => 'Dashboard', 'url' => ['/dashboard/stockist']],
                ['icon' => 'fa fa-hand-holding-usd', 'label' => 'Withdrawal', 'url' => ['/withdrawal/index']],
                ['icon' => 'fa fa-comments-dollar', 'label' => 'Transfer Ewallet', 'url' => ['/transfer/index']],

                ['icon' => 'fa fa-cart-arrow-down', 'label' => 'Buy', 'url' => ['/buy/index']],
                ['icon' => 'fa fa-user', 'label' => 'Register', 'url' => ['/register/index']],
                ['icon' => 'fa fa-exchange-alt', 'label' => 'Transaction', 'url' => ['/transaction/index']],
                ['icon' => 'fa fa-portrait', 'label' => 'Profile', 'url' => ['/profile/index'], 'hidden' => true],
            ];
            array_push($menuNav, $menuMember);
        } else if ($_user && $_user->isStockist()) {
            $menuNav = [
                ['icon' => 'fa fa-home', 'label' => 'Dashboard', 'url' => ['/dashboard/stockist']],
                ['icon' => 'fa fa-hand-holding-usd', 'label' => 'Withdrawal', 'url' => ['/withdrawal/index']],
                ['icon' => 'fa fa-comments-dollar', 'label' => 'Transfer Ewallet', 'url' => ['/transfer/index']],
                ['icon' => 'fa fa-cart-arrow-down', 'label' => 'Buy', 'url' => ['/buy/index']],
                ['icon' => 'fa fa-user', 'label' => 'Register', 'url' => ['/register/index']],
                ['icon' => 'fa fa-exchange-alt', 'label' => 'Transaction', 'url' => ['/transaction/index']],
                ['icon' => 'fa fa-portrait', 'label' => 'Profile', 'url' => ['/profile/index'], 'hidden' => true],
            ];
            array_push($menuNav, $menuMember);
        } else if ($_user && $_user->isMobile()) {
            $menuNav = [
                ['icon' => 'fa fa-home', 'label' => 'Dashboard', 'url' => ['/dashboard/stockist']],
                ['icon' => 'fa fa-hand-holding-usd', 'label' => 'Withdrawal', 'url' => ['/withdrawal/index']],
                ['icon' => 'fa fa-comments-dollar', 'label' => 'Transfer Ewallet', 'url' => ['/transfer/index']],
                ['icon' => 'fa fa-cart-arrow-down', 'label' => 'Buy', 'url' => ['/buy/index']],
                ['icon' => 'fa fa-user', 'label' => 'Register', 'url' => ['/register/index']],
                ['icon' => 'fa fa-exchange-alt', 'label' => 'Transaction', 'url' => ['/transaction/index']],
                ['icon' => 'fa fa-portrait', 'label' => 'Profile', 'url' => ['/profile/index'], 'hidden' => true],
            ];
            array_push($menuNav, $menuMember);
        } else if ($_user && $_user->isMember()) {
            $menuNav = [
                ['icon' => 'fa fa-home', 'label' => 'Dashboard', 'url' => ['/dashboard/member']],
                ['icon' => 'fa fa-network-wired', 'label' => 'Network', 'url' => ['/network/index']],
                ['icon' => 'fa fa-cart-arrow-down', 'label' => 'Buy', 'url' => ['/buy-product/index']],
                ['icon' => 'fa fa-user-friends', 'label' => 'Downline', 'url' => ['/user/downline']],
                ['icon' => 'fa fa-hand-holding-usd', 'label' => 'Withdrawal', 'url' => ['/withdrawal/index']],
                ['icon' => 'fa fa-usd', 'label' => ' Point Redeem', 'url' => ['/point-redeem/index']],
                ['icon' => 'fa fa-comments-dollar', 'label' => 'Transfer Ewallet', 'url' => ['/transfer/index']],
                ['icon' => 'fa fa-exchange-alt', 'label' => 'Transaction', 'url' => ['/transaction/index']],
                ['icon' => 'fa fa-usd', 'label' => 'Transaction Points', 'url' => ['/view-point/index']],
                ['icon' => 'fa fa-user-secret', 'label' => 'Stockist', 'url' => ['/list-stockist/index']],
                ['icon' => 'fa fa-portrait', 'label' => 'Profile', 'url' => ['/profile/index'], 'hidden' => true],
            ];
            array_push($menuNav, $menuMember);
        } else if ($_user && $_user->isProgrammer()) {
            $menuNav = [
                ['icon' => 'fa fa-home', 'label' => 'Dashboard', 'url' => ['/dashboard/programmer']],
                ['icon' => 'fa fa-hand-holding-usd', 'label' => 'Withdrawal', 'url' => ['/withdrawal/index']],
                ['icon' => 'fa fa-comments-dollar', 'label' => 'Transfer Ewallet', 'url' => ['/transfer/index']],
                ['icon' => 'fa fa-exchange-alt', 'label' => 'Transaction', 'url' => ['/transaction/index']],
                ['icon' => 'fa fa-portrait', 'label' => 'Profile', 'url' => ['/profile/index'], 'hidden' => true],
            ];
            array_push($menuNav, $menuMember);
        } else if ($_user && $_user->isMerchant()) {
            $menuNav = [
                ['icon' => 'fa fa-home', 'label' => 'Dashboard', 'url' => ['/dashboard/merchant']],
                ['icon' => 'fa fa-hand-holding-usd', 'label' => 'Point', 'url' => ['/point-search/index']],
                ['icon' => 'fa fa-money', 'label' => 'Payment', 'url' => ['/point-payment/index']],
                ['icon' => 'fa fa-exchange-alt', 'label' => 'Transaction', 'url' => ['/transaction-point/index']],
                ['icon' => 'fa fa-portrait', 'label' => 'Profile', 'url' => ['/profile/index'], 'hidden' => true],
            ];
            array_push($menuNav, $menuMember);
        } else {
            $menuNav = $menuGuest;
        }
        return $menuNav;
    }

    public function checkAllow()
    {
        $menu = $this->listMenu();

        $allow = [];
        foreach ($menu as $value) {

            $url = explode("/", $value['url'][0]);
            $allow[] = $url[1];
        }
        return $allow;
    }

    public function run()
    {
        $session = Yii::$app->session;
        if (isset($session['subMenu']))
            $this->subMenu = $session['subMenu'];
        if (isset($session['subBtn']))
            $this->subBtn = $session['subBtn'];
        $list = $this->listMenu();
        $str = '<ul class="sidebar-menu" id="nav-accordion">';
        foreach ($list as $value) {
            if (!isset($value['hidden']) || !$value['hidden']) {
                $urlExplode = explode('/', $value['url'][0]);
                $select = (strtoupper($this->idPage) == strtoupper($urlExplode[1])) ? true : false;
                $str .= '<li><a class="' . ($select ? 'active' : '') . '" href="' . Url::to($value['url']) . '"><i class="' . $value['icon'] . '"></i>' . $value['label'] . '</a>';
                $str .= '</li>';
            }
        }
        $str .= '</ul>';
        return $str;
    }
}
