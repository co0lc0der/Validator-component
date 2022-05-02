<?php
// $config = require __DIR__ . '/QueryBuilder/config.php';
// require __DIR__ . '/QueryBuilder/Connection.php';
// require __DIR__ . '/QueryBuilder/QueryBuilder.php';

include __DIR__ . '/Validator/Validator.php';

// $query = new QueryBuilder(Connection::make($config['database']));

if (!empty($_POST)) {
  $validator = new Validator();
  // $validator = new Validator($query);

  $validator->check($_POST, [
    'username'  =>  [
      'required'  =>  true,
      'min'   =>  2,
      'max'   =>  15,
    ],
    'email' =>  [
      'required'  =>  true,
      'email' =>  true,
      //'unique'    =>  'users'
    ],
    'password' => [
      'required'  =>  true,
      'min'   =>  3
    ],
    'password_again' => [
      'required'  =>  true,
      'matches'   => 'password'
    ],
    'number'  =>  [
      'required'  =>  true,
      'max'   =>  5,
      'min_value'   =>  0,
      'max_value'   =>  15,
    ],
    'date' => [
      'required'  =>  true,
      'regex' => "/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/" // YYYY-MM-DD
    ],
    'agree' => [
      'required'  =>  true,
    ]
  ]);

  if ($validator->passed()) {
    // do something
  } else {
    echo '<ul>';
    foreach ($validator->errors() as $error) {
      echo "<li>$error</li>";
    }
    echo '</ul>';
  }
}
?>

<form action="" method="post" enctype="multipart/form-data">
  <input type="text" name="username" value="<?= $_POST['username'] ?? ''; ?>"><br>
  <input type="email" name="email" value="<?= $_POST['email'] ?? ''; ?>"><br>
  <input type="password" name="password" value="<?= $_POST['password'] ?? ''; ?>"><br>
  <input type="password" name="password_again" value="<?= $_POST['password_again'] ?? ''; ?>"><br>
  <input type="number" name="number" value="<?= $_POST['number'] ?? ''; ?>"><br>
  <input type="text" name="date" value="<?= $_POST['date'] ?? ''; ?>" placeholder="YYYY-MM-DD"><br>
  <input type="checkbox" name="agree" <?= isset($_POST['agree']) && !empty($_POST['agree']) ? 'checked' : '' ?>> I agree<br>
  <input type="submit" value="Send">
</form>
