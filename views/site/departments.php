<h2>Подразделения</h2>

<div style="display: flex; gap: 40px;">
    <section style="flex: 1;">
        <h3>Список подразделений</h3>
        <table border="1" width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Вид подразделения</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $dept): ?>
                <tr>
                    <td><?= (int)$dept->departments_id ?></td>
                    <td><?= e($dept->name) ?></td>
                    <td><?= e($dept->type) ?></td>
                    <td>
                        <a href="<?= app()->route->getUrl('/department/phones') ?>?id=<?= (int)$dept->departments_id ?>">Номера абонентов</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <?php if (app()->auth::check() && (app()->auth::user()->role === 'admin' || app()->auth::user()->role === 'super_admin')): ?>
    <section style="flex: 1;">
        <h3>Добавить подразделение</h3>
        <form method="post" action="<?= app()->route->getUrl('/department/create') ?>">
            <?= csrf_field() ?>
            <div>
                <label>Название подразделения:</label><br>
                <input type="text" name="name" placeholder="Напр: Отдел IT" required>
            </div>
            <div>
                <label>Вид подразделения:</label><br>
                <input type="text" name="type" placeholder="Напр: Департамент / Отдел / Кафедра" required>
            </div>
            <br>
            <button type="submit">Добавить подразделение</button>
        </form>
    </section>
    <?php endif; ?>
</div>
