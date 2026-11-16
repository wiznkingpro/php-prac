<h2>Панель системного администратора</h2>

<div style="display: flex; gap: 40px;">
    <section style="flex: 1;">
        <h3>Список пользователей</h3>
        <table border="1" width="100%">
            <thead>
                <tr>
                    <th>Логин</th>
                    <th>ФИО</th>
                    <th>Роль</th>
                    <th>Департамент</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= e($u->login) ?></td>
                    <td><?= e("{$u->last_name} {$u->first_name}") ?></td>
                    <td><?= e($u->role) ?></td>
                    <td><?= e($u->department->name ?? '—') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section style="flex: 1;">
        <h3>Создать пользователя</h3>
        <form method="post" action="<?= app()->route->getUrl('/admin/user/create') ?>">
            <?= csrf_field() ?>
            <div>
                <label>Логин:</label><br>
                <input type="text" name="login" required>
            </div>
            <div>
                <label>Пароль:</label><br>
                <input type="password" name="password" required>
            </div>
            <div>
                <label>Фамилия:</label><br>
                <input type="text" name="last_name" required>
            </div>
            <div>
                <label>Имя:</label><br>
                <input type="text" name="first_name" required>
            </div>
            <div>
                <label>Отчество:</label><br>
                <input type="text" name="middle_name">
            </div>
            <div>
                <label>Роль:</label><br>
                <select name="role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div>
                <label>Департамент:</label><br>
                <select name="departments_id" required>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= (int)$dept->departments_id ?>"><?= e($dept->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <br>
            <button type="submit">Создать</button>
        </form>
    </section>
</div>
