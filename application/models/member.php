<?php

class Member extends CI_Model
{
    private $input_data = array(
        'user_id' => null,
        'pass' => null,
    );

    public function __construct($data = array())
    {
        parent::__construct();
        foreach ($data as $key => $value){
            $this ->input_data[$key] = $value;
        }
    }

    /**
     * 登入檢查
     *
     * @param string $whereDscArray
     * @return bool
     */
    public function frontLoginCheck($whereDscArray = '')
    {
        $result_value = false;
        if( isset($this->input_data['user_id']) AND isset($this->input_data['pass'])){
            $this->load->database();
            $this->load->library('session');
            $getDataArray = array();
            $user_data = array();
            $this->db->where('user_id', $this->input_data['user_id']);
            $this->db->where('pass',  md5($this->input_data['user_id']));
            $query = $this->db->get('user_info')->result();
            if (count($query) == 1) {
                foreach ($query as $getkey => $getData) {
                    $getData = array(
                        'loginType' => 'frontUser',
                        'userName' => $getData->mName
                    );
                    $this->session->set_userdata($getData);
                }

                $result_value = true;
            }
        }

        return $result_value;
    }

}