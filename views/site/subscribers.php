<h2>Справочник абонентов</h2>
<form method="get" style="margin-bottom: 20px;">
    <input type="text" name="search" placeholder="Фамилия или номер телефона..." value="<?= e($_GET['search'] ?? '') ?>">
    <button type="submit">Поиск</button>
    <a href="<?= app()->route->getUrl('/') ?>">Сбросить</a>
</form>

<table border="1" width="100%" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th>ФИО</th>
            <th>Дата рождения</th>
            <th>Помещение (Подразделение)</th>
            <th>Телефон</th>
            <?php if (app()->auth::check() && (app()->auth::user()->role === 'admin' || app()->auth::user()->role === 'super_admin')): ?>
                <th>Действия</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($subscribers as $sub): ?>
        <tr>
            <td>
                <?= e("{$sub->last_name} {$sub->first_name} {$sub->middle_name}") ?>
            </td>
            <td><?= e($sub->birth_date) ?></td>
            <td>
                <?= e(($sub->room->room_name ?? '—') . ' (' . ($sub->room->department->name ?? '—') . ')') ?>
            </td>
            <td>
                <?php foreach ($sub->phones as $phone): ?>
                    <?= e($phone->phone_number) ?><br>
                <?php endforeach; ?>
                <a href="<?= app()->route->getUrl('/subscriber/phones') ?>?id=<?= (int)$sub->subscribers_id ?>" style="font-size: 12px;">[все номера]</a>
            </td>
            <?php if (app()->auth::check() && (app()->auth::user()->role === 'admin' || app()->auth::user()->role === 'super_admin')): ?>
                <td>
                    <a href="<?= app()->route->getUrl('/subscriber/edit') ?>?id=<?= (int)$sub->subscribers_id ?>">Редактировать</a>
                    
                    <form method="post" action="<?= app()->route->getUrl('/subscriber/delete') ?>" style="display:inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int)$sub->subscribers_id ?>">
                        <button type="submit" onclick="return confirm('Удалить?')">Удалить</button>
                    </form>
                </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>