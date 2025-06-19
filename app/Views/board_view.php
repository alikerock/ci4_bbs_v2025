<h2>Board View</h2>
<?php
  echo '<pre>';
  print_r($view);
  echo '</pre>';
?>
<h3><?= $view->subject; ?></h3>
<div>
  <?= $view->content; ?>  
</div>  
<div>
  <!-- 첨부파일 출력 -->
  <?php 
    if(isset($view->fa)){
      $farr = explode(',',$view->fa);
      $ftarr = explode(',',$view->ft);
      $i = 0;
      foreach($farr as $far){

        if($ftarr[$i] === 'image'){ //이미지라면
          echo "<img src=\"".base_url('/uploads/'.$far)."\" alt=\"\">";
        } else { //아니라면
          echo "<a href=\"".base_url('/uploads/'.$far)."\" download >다운로드</a>";
        }
        $i++;
      }
    }
  ?>
</div>



<hr>
<div class="text-end">
  <?php
    if(isset($_SESSION['userid']) && $_SESSION['userid'] === $view->userid){
  ?>
    <a href="/modify/<?= $view->bid; ?>" class="btn btn-secondary btn-sm">수정</a>
    <a href="/delete/<?= $view->bid; ?>" class="btn btn-danger btn-sm">삭제</a>
  <?php
  }
  ?>
  <a href="/board" class="btn btn-primary btn-sm">목록</a>
</div>
