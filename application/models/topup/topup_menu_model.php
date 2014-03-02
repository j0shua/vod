<?php

class topup_menu_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function main_side_menu($active = '') {
        $menu_data['main_title'] = 'ระบบเติมเงิน';
        $menu_data['active'] = $active;
        $menu_data['menu']['topup_manager'] = array('title' => 'การเติมเงิน', 'uri' => 'utopup/manual_topup');
        $menu_data['menu']['informant_manager'] = array('title' => 'การโอนเงินเติม', 'uri' => 'utopup/manual_topup/informant_manager');
        $menu_data['menu']['truemoney'] = array('title' => 'การเติมเงิน TRUE', 'uri' => 'utopup/truemoney');
        $menu_data['menu']['coupon_manager'] = array('title' => 'จัดการคูปอง', 'uri' => 'utopup/coupon_manager');
        $menu_data['menu']['coupon_used'] = array('title' => 'การใช้คูปอง', 'uri' => 'utopup/coupon_manager/coupon_used');
        $menu_data['menu']['coupon_fail'] = array('title' => 'คูปองที่กรอกผิด', 'uri' => 'utopup/coupon_manager/coupon_fail');
        return $this->load->view('main_side_menu', $menu_data, TRUE);
    }

}
