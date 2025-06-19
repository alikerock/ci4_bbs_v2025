<?php
namespace App\Controllers;
use App\Models\BoardModel;  //사용할 모델 로드
use App\Models\FileModel;  //사용할 모델 로드


class Board extends BaseController
{

    
    public function list(): string
    {
               
        /*
            게시판 테이블 글조회(구식)
            $db = db_connect(); //DB연결
            $sql = "SELECT * FROM ci4board ORDER BY bid DESC";
            $rs = $db->query($sql);
            $data['list'] = $rs->getResult(); //모든 결과 변환 배열에 할당
            $data = ['list'=>$rs->getResult()]; 
            조회결과를 data배열의 키list에 할당
        */
        //게시판 테이블 글조회(모델)
        $page = $this->request->getVar('page') ?? 1;
        $perPage = 10;
        $startNum = ($page - 1) * 10;

        $boardModel = new BoardModel(); //DB연결, 테이블조작...준비
        $data['list'] = $boardModel
                    ->orderBy('bid', 'desc')
                    ->limit($perPage, $startNum)
                    ->findAll();
        //총게시물 수
        $total = $boardModel->countAllResults();
        
        //페이저 서비스 시작(로드)
        $pager = service('pager');
        
        //페이저 생성        
        $pager_links = $pager->makeLinks($page, $perPage, $total,'default_full');
        $data['pager_links'] = $pager_links;

        // return view('board_list');
        return render('board_list',$data);
    }
    public function write()
    {        
        if(!isset($_SESSION['userid'])){
            return redirect()->to('/login')->with('alert', '로그인하세요');// alert 세션 생셩
        }
        return render('board_write');
    }

    public function save(){
        /*
        //글저장(구식)
        $db = db_connect();
        $subject = $this->request->getVar('subject');
        $content = $this->request->getVar('content');
     
        $sql = "INSERT INTO ci4board (subject, content) VALUES ('{$subject}', '{$content}')";
        $rs = $db->query($sql);   
        */
        $boardModel = new BoardModel();
        $fileModel = new FileModel();
        $data = [
            'userid' => $this->request->getVar('userid'),
            'subject' => $this->request->getVar('subject'),
            'content' => $this->request->getVar('content')
        ];
        /* 
        글쓰기 페이지에서 이미 파일 모두처리하기 때문에 주석
        //$file = $this->request->getFile('upfile');//첨부파일 하나를 $file에 할당
        //$files = $this->request->getFileMultiple('upfile');//다중파일 $files에 할당

        //$filepath = array();
        //$filepath = [];
        //$isImage = [];
        /*
        foreach($files as $file){
            if($file->getName()){
                $tempPath = $file->getTempName(); //임시파일 정보
                // getimagesize()로 실제 이미지인지 확인
                $isImage[] = getimagesize($tempPath) ? 'image':'';
    
                // $filename = $file->getName(); 
                $newName = $file->getRandomName();
                $filepath[] = $file->store('board/',$newName); 
            }
        }
        */
        

        $bid = $this->request->getVar('bid');        
        
        if($bid){
            //글 수정
            $board = $boardModel->find($bid); //bid 글 조회
            if($board){ //수정할 글이 맞다면(있다면)
                $boardModel->update($bid, $data);//글 수정
                return $this->response->redirect(site_url('boardView/'.$bid));//수정글 보기
            }
        } else{
            //새글 저장            
            $boardModel->insert($data);   //새글 저장
            $insertId = $boardModel->getInsertID();//저장된 새글을 id
            $fids = $this->request->getVar('file_table_id'); //예 ,23,24  file_table에서 bid를 갱신할 번호들
            $filterdFids = ltrim($fids, ','); //예 23,24
            $fidArray = explode(',',$filterdFids); //예 [23,24]

            $fileModel->whereIn('fid', $fidArray)->set(['bid' => $insertId])->update();
            /*
            글쓰기 페이지에서 이미 파일 모두처리하기 때문에 주석
            $i = 0;
            foreach($filepath as $fp){
                $fileData = [
                    'bid' => $insertId,
                    'userid' => $this->request->getVar('userid'),
                    'filename' => $fp,
                    'type' => $isImage[$i]
                ]; 
                $fileModel->insert($fileData);   //첨부파일 저장
                $i++;
            }
            */
            //저장후 목록 페이지 이동
            return $this->response->redirect(site_url('board'));
        }

    }
    public function save_image(){

        $fileModel = new FileModel();
        $file = $this->request->getFile('attachment');//첨부파일 하나를 $file에 할당

        if($file->getName()){
            $tempPath = $file->getTempName(); //임시파일 정보
            $filename = $file->getName();
            $isImage = getimagesize($tempPath) ? 'image':'';
            $newName = $file->getRandomName();
            $filepath = $file->store('board/',$newName); 
        }               

        //새파일 저장           

        $fileData = [
            'bid' => '',
            'userid' => session('userid'),
            'filename' => $filepath,
            'type' => $isImage
        ]; 
        $fileModel->insert($fileData);   //첨부파일 저장
        $insertId = $fileModel->getInsertID();//새글 fid 생성

        //저장후 결과를 json 형식으로 반환
        if($insertId){
            $result = array('message'=>'ok', 'fid'=>$insertId, 'name'=> $filename, 'url'=>$filepath);
            return json_encode($result);
        }else{
            $result = array('message'=>'fail');
            return json_encode($result);
        }
    }
    public function file_delete(){

        $fileModel = new FileModel();
        $fid = $this->request->getVar('fid');  

        $file = $fileModel->where('fid', $fid)->first(); //삭제 파일 정보 조회
        unlink('uploads/'.$file->filename); //서버 파일 삭제
        $fileModel->delete($fid); //테이블에서 삭제

    }

    public function view($bid=null): string {
        /*
        //bid로 글조회(구식)
        $db = db_connect(); //DB연결
        $sql = "SELECT * FROM ci4board WHERE bid={$bid}";
        $rs = $db->query($sql);        
        $data['view'] = $rs->getRow(); 
        //쿼리실행결과에서 데이터를 object 형식추출, 변수 $data에 할당
        */
        //bid로 글조회(모델)
        $boardModel = new BoardModel();
        $fileModel = new FileModel();
        /*
        각 테이블의 정보를 view, file_view 따로 저장.
        $data['view'] = $boardModel->where('bid', $bid)->first();

        //$data['file_view'] = $fileModel->find($bid); //find인수는 PK, $bid pk
        $data['file_view'] = $fileModel->where('bid', $bid)->first();
        */
        //Join 조회후, view 저장

        /*
        $data['view'] = $boardModel->select('b.*,f.filename')
                    ->from('board b')                            
                    ->join('file_table f','f.bid = b.bid', 'left')                            
                    ->where('b.bid', $bid)
                    ->first();
        */
        $data['view'] = $boardModel
        ->select('b.*, fa.fa, ft.ft')
        ->from('ci4board b')
        ->join('(SELECT bid, GROUP_CONCAT(filename) AS fa FROM file_table GROUP BY bid) fa', 'fa.bid = b.bid', 'left')
        ->join('(SELECT bid, GROUP_CONCAT(type) AS ft FROM file_table GROUP BY bid) ft', 'ft.bid = b.bid', 'left')
        ->where('b.bid', $bid)
        ->first();  

        return render('board_view', $data);
    }
    public function modify($bid=null)    {

        //bid로 글조회(모델)
        $boardModel = new BoardModel();
        $data['view'] = $boardModel->where('bid', $bid)->first();


        return render('board_write', $data);
    }
    public function delete($bid=null)    {
        //$bid로 해당글을 삭제하고 글 목록으로 이동
        $boardModel = new BoardModel();
        $fileModel = new FileModel();

        $board = $boardModel->find($bid); //bid로 해당 글 조회

        if($board && session('userid') === $board->userid){ //글을 쓴 유저라면
            $boardModel->delete($bid);
            $files = $fileModel->where('bid', $bid)->findAll(); //bid와 일치하는 파일정보 조회

            foreach($files as $file){
                unlink('uploads/'.$file->filename); //서버에 실제 파일 삭제
            }

            $fileModel->where('bid', $bid)->delete();// file 테이블에서 해당글 삭제      

            return $this->response->redirect(site_url('board'));
        }else{
            return redirect()->to('/')->with('alert', '본인글만 삭제할 수 있습니다.');// alert 세션 생셩
        }

    }
}
