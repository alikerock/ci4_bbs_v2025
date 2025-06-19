<h2>Board Write</h2>
<form action="<?= site_url('writeSave')?><?= isset($view->bid) ?  '/?bid='.$view->bid : '';?>" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="userid" value="<?= $_SESSION['userid']; ?>">
  <input type="hidden" name="file_table_id" id="file_table_id" value="">

  <div class="mb-3">
    <label for="subject" class="form-label">제목</label>
    <input type="text" name="subject" class="form-control" id="subject" placeholder="제목을 입력해주세요" value="<?= isset($view->subject) ?  $view->subject : '';?>">
  </div>
  <div class="mb-3">
    <label for="content" class="form-label">내용</label>
    <textarea class="form-control" name="content" id="content" rows="3"><?= isset($view->content) ?  $view->content : '';?></textarea>
  </div>
  <div class="mb-3">
    <button type="button" id="addFile" class="btn btn-sm btn-primary">파일 첨부</button>
    <input type="file" class="d-none" multiple name="upfile[]" class="form-control" id="upfile">
  </div>
  <div class="preview d-flex mb-3 gap-3">

    <!-- 
    <div class="card" style="width: 10rem;">
      <img src="..." class="card-img-top" alt="...">
      <div class="card-body">
        <h5 class="card-title">파일명</h5>        
        <button type="button" class="btn btn-sm btn-danger">삭제</button>
      </div>
    </div> -->


  </div>  
  <button class="btn btn-primary"><?= isset($view->bid) ?  '수정' : '등록';?></button>
</form>
<script>
  $('#addFile').click(function(){
    $('#upfile').trigger('click');
  })

  $('#upfile').change(function(e){
    // let files = e.target.files;
    let files = [...$(this).prop('files')];
    files.forEach(file => {
      fileUploads(file);
    }); 
  });

  $('.preview').on('click','button', function(){  
    $(this).closest('.card').remove();
    let fidStr = $(this).closest('.card').attr('id'); //f_18
    let fid = fidStr.replace('f_','');
    let fidValues = $('#file_table_id').val();
    let newfidValues = fidValues.replace(`,${fid}`,'');
    $('#file_table_id').val(newfidValues);

    fileDelete(fid);
  });

  function fileDelete(fid){ 
    
    //테이블 파일 삭제
    let data = {
      fid:fid
    }
    $.ajax({
      url:'/file_delete',
      data:data,
      type: 'post',      
      dataType:'json',
      success:function(result){
        if(result.message == 'ok'){
          alert('파일 삭제 완료');
        }else{
          alert('파일 삭제 실패');
        }
      },
      error:function(e){
        console.log(e);
      }
    })
  }
  function fileUploads(file){ 
    //미리보기   
    /*
    const reader = new FileReader(); 
    reader.addEventListener(
      "load", 
      (e) => { 
        let attachment = e.target.result;
        if(attachment){    
          //A.append(B)
          let item = `<div class="card" style="width: 10rem;">
            <img src="${attachment}" class="card-img-top" alt="...">
            <div class="card-body">
              <h5 class="card-title">${file.name}</h5>        
              <button type="button" class="btn btn-sm btn-danger">삭제</button>
            </div>
          </div>`;

          $('.preview').append(item);               
        }
      },
      false,
    );
    if (file) {
      reader.readAsDataURL(file); //3번, 파일 정보를 blob(text)형태로 추출(로드)
    }
    */
    //테이블 파일 저장
    var formData = new FormData(); // 빈 폼 객체 생성

      formData.append('attachment', file);

    $.ajax({
      url:'/save_image',
      data:formData,
      type: 'post',      
      contentType: false, //multipart/form-data
      processData:false, //문자열로 변환x, 파일원본(binary)
      dataType:'json',
      success:function(result){
        if(result.message == 'ok'){
          let fids = $('#file_table_id').val()+','+ result.fid;
          $('#file_table_id').val(fids);

          let item = `<div class="card" style="width: 10rem;" id="f_${result.fid}">
            <img src="uploads/${result.url}" class="card-img-top" alt="${result.name}">
            <div class="card-body">
              <h5 class="card-title">${result.name}</h5>        
              <button type="button" class="btn btn-sm btn-danger">삭제</button>
            </div>
          </div>`;
          $('.preview').append(item); 
        }else{
          alert('파일 첨부 실패, 다시 시도해주세요');
        }
      },
      error:function(e){
        console.log(e);
      }
    })

  }
</script>