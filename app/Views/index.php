<?php if(session('alert')): ?>
  <div class="alert alert-primary" role="alert">
    <?= session('alert') ?> 
  </div>
<?php endif; ?>

<h2>Welcome to ABC.com</h2>

<?php
// $hash1 = password_hash('1111', PASSWORD_DEFAULT);
// $hash2 = password_hash('1111', PASSWORD_DEFAULT);
// echo $hash1.'<br>';
// echo $hash2.'<br>';

// if(password_verify('1111', $hash1)){
//   echo "일치";
// }
// if(password_verify('1111', $hash2)){
//   echo "일치";
// }

  // unset(
  //   $_SESSION['userid'],
  //   $_SESSION['username'],
  //   $_SESSION['hash'],
  //   $_SESSION['password'],
  //   $_SESSION['result']
  // );

  // echo '<pre>';
  // print_r($_SESSION);
  // echo '</pre>';
?>