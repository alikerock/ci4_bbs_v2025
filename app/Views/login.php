<?php if(session('alert')): ?>
  <div class="alert alert-primary" role="alert">
    <?= session('alert') ?> 
  </div>
<?php endif; ?>


<h2>로그인</h2>
<form action="<?= site_url('loginOK')?>" method="POST">
  <div class="mb-3">
    <label for="userid" class="form-label">아이디</label>
    <input type="text" name="userid" class="form-control" id="userid" placeholder="아이디를 입력해주세요">
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">비밀번호</label>
    <input type="password" name="password" class="form-control" id="password" placeholder="비밀번호를 입력해주세요">
  </div>
  <button class="btn btn-primary">로그인</button>
</form>