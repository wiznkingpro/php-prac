<h2>Управление телефонами</h2>

<div style="display: flex; gap: 40px;">
    <section style="flex: 2;">
        <h3>Список всех телефонов</h3>
        <table border="1" width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Номер</th>
                    <th>Абонент</th>
                    <th>Помещение</th>
                    <th>Подразделение</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($phones as $phone): ?>
                <tr>
                    <td><b><?= e($phone->phone_number) ?></b></td>
                    <td>
                        <?php if ($phone->subscriber): ?>
                            <?= e("{$phone->subscriber->last_name} {$phone->subscriber->first_name}") ?>
                            <a href="<?= app()->route->getUrl('/subscriber/phones') ?>?id=<?= (int)$phone->subscriber->subscribers_id ?>" style="font-size:12px;">[все номера]</a>
                        <?php else: ?>
                            <i>Не прикреплен</i>
                        <?php endif; ?>
                    </td>
                    <td><?= e($phone->subscriber->room->room_name ?? '—') ?></td>
                    <td><?= e($phone->subscriber->room->department->name ?? '—') ?></td>
                    <td><?= e($phone->status) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <?php if (app()->auth::check() && (app()->auth::user()->role === 'admin' || app()->auth::user()->role === 'super_admin')): ?>
    <section style="flex: 1;">
        <h3>Прикрепить абонента к номеру</h3>
        <form method="post" action="<?= app()->route->getUrl('/phone/attach') ?>" style="margin-bottom: 30px;">
            <?= csrf_field() ?>
            <div>
                <label>Телефон:</label><br>
                <select name="phones_id" required>
                    <option value="" disabled selected>-- Выберите номер --</option>
                    <?php foreach ($phones as $p): ?>
                        <option value="<?= (int)$p->phones_id ?>">
                            <?= e($p->phone_number) ?> (<?= $p->subscriber ? e($p->subscriber->last_name) : 'свободен' ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Абонент:</label><br>
                <select name="subscribers_id" required>
                    <option value="" disabled selected>-- Выберите абонента --</option>
                    <?php foreach ($subscribers as $s): ?>
                        <option value="<?= (int)$s->subscribers_id ?>">
                            <?= e("{$s->last_name} {$s->first_name}") ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <br>
            <button type="submit">Прикрепить к номеру</button>
        </form>

        <hr>

        <h3>Добавить новый телефон</h3>
        <form method="post" action="<?= app()->route->getUrl('/phone/create') ?>">
            <?= csrf_field() ?>
            <div>
                <label>Номер телефона:</label><br>
                <input type="text" name="phone_number" placeholder="Напр: 89991234567" required>
            </div>
            <div>
                <label>Прикрепить абонента (опционально):</label><br>
                <select name="subscribers_id">
                    <option value="">-- Без прикрепления --</option>
                    <?php foreach ($subscribers as $s): ?>
                        <option value="<?= (int)$s->subscribers_id ?>">
                            <?= e("{$s->last_name} {$s->first_name}") ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <br>
            <button type="submit">Создать телефон</button>
        </form>
    </section>
    <?php endif; ?>
</div>
