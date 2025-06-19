<h2>회원가입</h2>
<form action="<?= site_url('registerOK')?>" method="POST">
  <div class="mb-3">
    <label for="userid" class="form-label">아이디</label>
    <input type="text" name="userid" class="form-control" id="userid" placeholder="아이디를 입력해주세요">
  </div>
  <div class="mb-3">
    <label for="username" class="form-label">이름</label>
    <input type="text" name="username" class="form-control" id="username" placeholder="이름을 입력해주세요">
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">비밀번호</label>
    <input type="password" name="password" class="form-control" id="password" placeholder="비밀번호를 입력해주세요">
  </div>
  <button class="btn btn-primary">회원가입</button>
</form>