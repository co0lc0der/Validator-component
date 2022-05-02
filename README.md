# Validator php component

This is easy-to-use php component for validate POST or GET data in your project. See `index.php` for examples.
### Public methods:
- `check()` - inits validation with rules
- `setDB()` - sets DB connection (using [QueryBuilder](https://github.com/co0lc0der/QueryBuilder-component) class)
- `errors()` - returns an array of validation's errors
- `passed()` - returns result of validation (true or false)
### Supported rules:
- `required` - true/false
- `min` - minimal length of value
- `max` - maximal length of value
- `matches` - compares 2 fields
- `int` - check integer type
- `min_value` - minimal value of integer
- `max_value` - maximal value of integer
- `unique` - looking for this value in DB
- `email` - check email correct format
- `regex` - any RegEx checking
## How to use
### 1. Include Validator class and init it. If you use 'unique' rule you have to inlude [QueryBuilder](https://github.com/co0lc0der/QueryBuilder-component) class also (see QueryBuilder README).
```php
require __DIR__ . '/Validator/Validator.php';
$validator = new Validator();
```
or
```php
$config = require __DIR__ . '/QueryBuilder/config.php';
require __DIR__ . '/QueryBuilder/Connection.php';
require __DIR__ . '/QueryBuilder/QueryBuilder.php';
require __DIR__ . '/Validator/Validator.php';

$query = new QueryBuilder(Connection::make($config['database']));
$validator = new Validator($query);
```
### 2. Use `check()` method with rules you need.
```php
$validator->check($_POST, [
  'username'  =>  [
    'required'  =>  true,
    'min'   =>  2,
    'max'   =>  15,
  ],
  'email' =>  [
    'required'  =>  true,
    'email' =>  true,
    'unique'    =>  'users'
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
```
form example:
```php
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
```
### 3. Do something if validation is passed or print errors.
```php
if ($validator->passed()) {
  // do something
} else {
  echo '<ul>';
  foreach ($validator->errors() as $error) {
    echo "<li>$error</li>";
  }
  echo '</ul>';
}
```
