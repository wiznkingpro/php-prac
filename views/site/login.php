<h2>Авторизация</h2>
<h3><?= $message ?? ''; ?></h3>
<form method="post">
   <input type="text" name="last_name" placeholder="Фамилия" required>
   <input type="password" name="password" placeholder="Пароль" required>
   <button type="submit">Войти</button>
</form>