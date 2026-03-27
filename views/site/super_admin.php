<h1>Панель Супер-Администратора</h1>

<div style="display: flex; gap: 50px;">
    <section>
        <h2>Справочник всех номеров</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Номер</th>
                    <th>Абонент</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($phones as $phone): ?>
                <tr>
                    <td><?= $phone->phone_number ?></td>
                    <td><?= $phone->subscriber->last_name ?? '—' ?></td>
                    <td><?= $phone->status ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section>
        <h2>Управление ролями</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Текущая роль</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $user): ?>
                <tr>
                    <td><?= $user->first_name ?> <?= $user->last_name ?></td>
                    <td><b><?= $user->role ?></b></td>
                    <td>
                        <form method="POST" action="/set-role">
                            <input type="hidden" name="user_id" value="<?= $user->users_id ?>">
                            <select name="role">
                                <option value="user" <?= $user->role == 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $user->role == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="super_admin" <?= $user->role == 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                            </select>
                            <button type="submit">Изменить</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>
