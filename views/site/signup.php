<h2>Регистрация нового пользователя</h2>
<h3><?= e($message ?? '') ?></h3>
<form method="post">
<?= csrf_field() ?>
<input type="text" name="login" placeholder="Логин" required>
<input type="text" name="last_name" placeholder="Фамилия" required>
<input type="text" name="first_name" placeholder="Имя" required>
<input type="text" name="middle_name" placeholder="Отчество">
<input type="password" name="password" placeholder="Пароль" required>
<input type="hidden" name="role" value="user">
<input type="hidden" name="status" value="active">
<label>Департамент:</label>
<select name="departments_id" required>
    <option value="" disabled selected>-- Выберите отдел --</option>
    <?php foreach ($departments as $dept): ?>
        <option value="<?= (int)$dept->departments_id ?>"><?= e($dept->name) ?></option>
    <?php endforeach; ?>
</select>
<button type="submit">Зарегистрировать</button>
</form>