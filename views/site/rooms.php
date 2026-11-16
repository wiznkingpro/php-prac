<h2>Помещения</h2>

<div style="display: flex; gap: 40px;">
    <section style="flex: 1;">
        <h3>Список помещений</h3>
        <table border="1" width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название/Номер</th>
                    <th>Вид помещения</th>
                    <th>Подразделение</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                <tr>
                    <td><?= (int)$room->rooms_id ?></td>
                    <td><?= e($room->room_name) ?></td>
                    <td><?= e($room->room_type) ?></td>
                    <td><?= e($room->department->name ?? '—') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <?php if (app()->auth::check() && (app()->auth::user()->role === 'admin' || app()->auth::user()->role === 'super_admin')): ?>
    <section style="flex: 1;">
        <h3>Добавить помещение</h3>
        <form method="post" action="<?= app()->route->getUrl('/room/create') ?>">
            <?= csrf_field() ?>
            <div>
                <label>Название / номер помещения:</label><br>
                <input type="text" name="room_name" placeholder="Напр: 402 / Кабинет 10" required>
            </div>
            <div>
                <label>Вид помещения:</label><br>
                <input type="text" name="room_type" placeholder="Напр: Кабинет / Аудитория / Лаборатория" required>
            </div>
            <div>
                <label>Подразделение:</label><br>
                <select name="departments_id" required>
                    <option value="" disabled selected>-- Выберите подразделение --</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= (int)$dept->departments_id ?>"><?= e($dept->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <br>
            <button type="submit">Добавить помещение</button>
        </form>
    </section>
    <?php endif; ?>
</div>
