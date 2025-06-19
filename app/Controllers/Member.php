<?php
namespace App\Controllers;
use App\Models\MemberModel;  //사용할 모델 로드

class Member extends BaseController
{

    public function login()
    {
        return render('login');
    }   
    public function register()
    {
        return render('register');
    }   
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }   
    public function loginok()
    {
        /*
        id, password를 변수에 할당
        members테이블에서 id, password로 조회
        일치하면
            세션데이터 생성, 홈으로 이동
        일치x
            로그인 페이지 이동
        */
        /*
        // 아이디 비번조회(구식)
        $db = db_connect();
        $userid = $this->request->getVar('userid');
        $password = $this->request->getVar('password'); 
     
        $sql = "SELECT * FROM members WHERE userid=?";
        $rs = $db->query($sql, [$userid]);
        $row = $rs->getRow();
        */
        $MemberModel = new MemberModel();
        $userid = $this->request->getVar('userid');
        $password = $this->request->getVar('password'); 
     
        $user = $MemberModel->where('userid', $userid)->first();
        
        if ($user && password_verify($password, $user->passwd)) {
            // 로그인 성공
            $data = [
                'userid'  => $user->userid,
                'username' => $user->username
            ];            
            $this->session->set($data);
            return redirect()->to('/');
        } else{
             return redirect()->to('/login')->with('alert', '다시 시도해주세요');// alert 세션 생셩
        }        
        
    }   
    public function registerok()
    {
        //회원가입(모델)
        $MemberModel = new MemberModel();
        $password = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);
        $data = [
            'userid' => $this->request->getVar('userid'),
            'username' => $this->request->getVar('username'),
            'passwd' => $password
        ];
        $MemberModel->insert($data);

        //회원가입 완료후 홈 이동
        return $this->response->redirect(site_url('/'));
    }
}
