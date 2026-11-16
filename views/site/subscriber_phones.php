<h2>Номера абонента</h2>

<?php if ($subscriber): ?>
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <h3><?= e("{$subscriber->last_name} {$subscriber->first_name} {$subscriber->middle_name}") ?></h3>
        <p><strong>Дата рождения:</strong> <?= e($subscriber->birth_date) ?></p>
        <p><strong>Помещение:</strong> <?= e($subscriber->room->room_name ?? '—') ?> (<?= e($subscriber->room->room_type ?? '') ?>)</p>
        <p><strong>Подразделение:</strong> <?= e($subscriber->room->department->name ?? '—') ?></p>
    </div>

    <h3>Закрепленные телефонные номера (всего: <?= count($phones) ?>):</h3>
    <table border="1" width="100%" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Номер телефона</th>
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($phones) > 0): ?>
                <?php foreach ($phones as $phone): ?>
                <tr>
                    <td><?= (int)($phone['phones_id'] ?? $phone->phones_id) ?></td>
                    <td><b><?= e($phone['phone_number'] ?? $phone->phone_number) ?></b></td>
                    <td><?= e($phone['status'] ?? $phone->status) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" style="text-align: center;">У абонента нет прикрепленных номеров</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Абонент не найден.</p>
<?php endif; ?>

<br>
<a href="<?= app()->route->getUrl('/') ?>">← Назад к списку абонентов</a>
