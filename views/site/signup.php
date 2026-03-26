<h2>Регистрация нового пользователя</h2>
<h3><?= $message ?? ''; ?></h3>
<form method="post">
   <input type="text" name="last_name" placeholder="Фамилия">
<input type="text" name="first_name" placeholder="Имя">
<input type="text" name="middle_name" placeholder="Отчество">
<input type="password" name="password" placeholder="Пароль">
<!-- Не забудьте скрытые или обязательные поля -->
<input type="hidden" name="role" value="user">
<input type="hidden" name="status" value="active">
<label>Департамент:</label>
<select name="departments_id" required>
    <option value="" disabled selected>-- Выберите отдел --</option>
    <?php foreach ($departments as $dept): ?>
        <option value="<?= $dept->departments_id ?>"><?= $dept->name ?></option>
    <?php endforeach; ?>
</select>
<button>Зарегистрироваться</button>
</form>