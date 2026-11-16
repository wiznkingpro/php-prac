<h2>Авторизация</h2>
<h3><?= e($message ?? '') ?></h3>
<form method="post">
   <?= csrf_field() ?>
   <input type="text" name="login" placeholder="Логин" required>
   <input type="password" name="password" placeholder="Пароль" required>
   <button type="submit">Войти</button>
</form>