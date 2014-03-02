<?php

/**
 * Description of bookdemo
 *
 * @author lojorider
 */
class book_demo extends CI_Controller {

    var $netConnectionUrl = 'rtmpe://www.pec9.com:1936/prokru';
    var $video_list = array(
        '20001' => array('title' => 'เลขนัยสำคัญ ระยะทางการกระจัด', 'video_path' => '201103/a4931451_4fcd8b3a.flv'),
        '10001' => array('title' => 'การเคลื่อนที่แนวเส้นตรง สมการการเคลื่อนที่', 'video_path' => '201103/a4931451_4fcd94c6.flv'),
        '20002' => array('title' => 'การเคลื่อนที่แบบต่างๆ การเคลื่นอนที่แบบโพรเจกไทล์', 'video_path' => '201103/a4931451_4ff17269.flv'),
        '10002' => array('title' => 'การเคลื่อนที่แบบต่างๆ การเคลื่นอนที่แบบโพรเจกไทล์2', 'video_path' => '201103/a4931451_4ff17d57.flv'),
        '10003' => array('title' => 'การเครื่อนที่แบบต่างๆ p03การเคลื่อนที่แบบแบบวงกลม', 'video_path' => '201103/a4931451_4ff16615.flv'),
        '20003' => array('title' => 'ไฟฟ้าแม่เหล็ก สนามแม่เหล็ก', 'video_path' => '201103/a4931451_503478e7.flv'),
        '10004' => array('title' => 'ไฟฟ้าแม่เหล็ก แรงผลักประจุไฟฟ้า', 'video_path' => '201103/a4931451_50347ef3.flv'),
        '10005' => array('title' => 'ไฟฟ้าแม่เหล็ก แรงผลักประจุไฟฟ้า2', 'video_path' => '201103/a4931451_50372678.flv'),
    );

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $this->login();
    }

    function login() {
        $menu = array(
            array(
                'text' => 'ลงทะเบียน',
                'title' => 'ลงทะเบียน',
                'href' => site_url('book_demo/register')
        ));
        $this->template->replace_topmenu($menu);
        $data = array(
            'form_title' => 'ลงชื่อเข้าใช้งาน',
            'form_action' => site_url('book_demo/do_login')
        );
        $this->template->write_view('book_demo/login_form', $data);
        $this->template->render();
    }

    function do_login() {

//        if ($this->auth->login($this->input->post('username'), $this->input->post('password'), $this->input->post('remember'))) {
//            $message = 'ลงชื่อเข้าใช้งานเสร็จสิ้น';
//        } else {
//            $message = 'ลงชื่อเข้าใช้งานไม่ได้';
//        }
        redirect(site_url('book_demo/search'));
    }

    function logout() {
        $this->auth->logout();
        redirect('user');
    }

    function register() {
        $menu = array(
            array(
                'text' => 'ลงชื่อเข้าใช้',
                'title' => 'ลงชื่อเข้าใช้',
                'href' => site_url('book_demo/login')
            )
        );
        $this->template->replace_topmenu($menu);
        $data = array(
            'form_title' => 'ลงทะเบียน',
            'form_action' => site_url('book_demo/do_login')
        );
        $this->template->write_view('book_demo/register_form', $data);
        $this->template->render();
    }

    function player() {
        $menu = array(
            array(
                'text' => 'ลงชื่อออก',
                'title' => 'ลงชื่อออก',
                'href' => site_url('book_demo/logout')
            )
        );
        $this->template->replace_topmenu($menu);
        $this->template->write_view('book_demo/player_web');
        $this->template->render();
    }

    function search() {
        $menu = array(
            array(
                'text' => 'เมนูหลัก',
                'title' => 'เมนูหลัก',
                'href' => site_url('book_demo/logout'),
                'sub_menu' => array(
                    array(
                        'text' => 'ข้อมูลส่วนตัว',
                        'title' => 'ข้อมูลส่วนตัว',
                        'href' => '#'
                    ),
                    array(
                        'text' => 'ค้นหาสื่อ',
                        'title' => 'ค้นหาสื่อ',
                        'href' => site_url('book_demo/search')
                    )
                )
            ),
            array(
                'text' => 'ลงชื่อออก',
                'title' => 'ลงชื่อออก',
                'href' => site_url('book_demo/logout')
            )
        );
        $this->template->replace_topmenu($menu);
        $data = array(
            'form_title' => 'ค้นหาสื่อ',
            'form_action' => site_url('book_demo/do_search')
        );
        $this->template->write_view('book_demo/search_form', $data);
        $this->template->render();
    }

    function do_search() {
        $id = $this->input->post('search_text');
        $mystring = $id;
        $findme = 'v';
        $pos = strpos($mystring, $findme);

        if ($pos === false) {
            redirect('v' . $id);
        } else {
            redirect($id);
        }
    }

    function play($id) {
        $menu = array(
            array(
                'text' => 'เมนูหลัก',
                'title' => 'เมนูหลัก',
                'href' => site_url('book_demo/logout'),
                'sub_menu' => array(
                    array(
                        'text' => 'ข้อมูลส่วนตัว',
                        'title' => 'ข้อมูลส่วนตัว',
                        'href' => '#'
                    ),
                    array(
                        'text' => 'ค้นหาสื่อ',
                        'title' => 'ค้นหาสื่อ',
                        'href' => site('book_demo/search')
                    )
                )
            ),
            array(
                'text' => 'ลงชื่อออก',
                'title' => 'ลงชื่อออก',
                'href' => site_url('book_demo/logout')
            )
        );
        $this->template->replace_topmenu($menu);

        if (!array_key_exists($id, $this->video_list)) {
            $id = '20001';
        }
        $data = array(
            'form_title' => $this->video_list[$id]['title'],
            'form_action' => site_url('book_demo/do_login'),
            'netConnectionUrl' => $this->netConnectionUrl,
            'video_path' => $this->video_list[$id]['video_path']
        );

        $play_form = 'book_demo/player_web';
        if ($this->agent->is_mobile()) {
            $play_form = 'book_demo/player_mobile';
            $this->template->temmplate_name('blank_html');
        }

        $this->template->load_flowplayer();
        $this->template->write_view($play_form, $data);

        $this->template->render();
    }

}