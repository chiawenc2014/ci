<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {

    /**
     * 檢查是否已經登入：
     *
     * 已經登入導到對應的入口頁
     * 沒有登入導到登入頁
     */
	public function index()
	{
        $this->load->library(array('session'));
        $this->load->helper(array('url'));
        if($this->session->userdata("loginType") == "" ){

            redirect('/member/login_page', 'refresh');
        }else{

            redirect('/questionslist/', 'refresh');
        }
	}

    /**
     * 登入頁面
     */
    public function login_page()
    {
        $data['base']= $this->config->item('base_url');
        $this->load->view('view_page/member/login',$data);
    }

    /**
     * 登入檢查
     */
	public function login_check()
    {
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('url','form'));
        if($this->session->userdata("loginType")>"" ){
            redirect('/questionslist/', 'refresh');
        }
        $this->form_validation->set_rules('username', 'username','required');
        $this->form_validation->set_rules('password', 'password', 'required|md5');//密碼強制轉成md5格式
        $this->form_validation->set_error_delimiters('', '');
        //$this->form_validation->set_message('required', '驗證碼欄位必填');
        //表單內容初步檢查失敗的話
        if ($this->form_validation->run() == FALSE)
        {

            redirect('/member/login_page', 'refresh');
        }
        else
        {
            $chkISOK = $this->RootLoginCheck();//管理員登入？
            if($chkISOK){
                redirect('/questionslist/', 'refresh');
            }
            $chkISOK = $this->TeacherLoginCheck();//教師登入？
            if($chkISOK){
                redirect('/questionslist/', 'refresh');
            }else{
                $menu_dscArray = array(
                    'languageDsc'=>$this->lang->line('menu_login_languageDsc'),
                    'userId'=>$this->lang->line('menu_login_id'),
                    'userPw'=>$this->lang->line('menu_login_pw'),
                    'clear'=>$this->lang->line('menu_login_clear'),
                    'submit'=>$this->lang->line('menu_login_submit'),
                    'ck_err1'=>$this->lang->line('menu_login_ck_err1'),
                    'ck_err2'=>$this->lang->line('menu_login_ck_err2'),
                );

                $data['base']= $this->config->item('base_url');
                $data['swLanguage']=$this->session->userdata("swLanguage");
                $data['errDSC'] = "錯誤的帳號或密碼!!";
                $data['verificationpic'] = $this->verificationpic->get_VerificationPic();//驗證碼圖片
                $this->load->view('view/memck/login',$data);
            }
        }
    }
}
