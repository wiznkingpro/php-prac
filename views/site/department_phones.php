<h2>Номера абонентов по подразделениям</h2>

<form method="get" style="margin-bottom: 20px;">
    <label>Выберите подразделение:</label>
    <select name="id" onchange="this.form.submit()">
        <option value="">-- Все подразделения --</option>
        <?php foreach ($departments as $dept): ?>
            <option value="<?= (int)$dept->departments_id ?>" <?= ($selectedId == $dept->departments_id) ? 'selected' : '' ?>>
                <?= e($dept->name) ?> (<?= e($dept->type) ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Показать</button>
</form>

<?php if ($selectedDepartment): ?>
    <h3>Подразделение: <?= e($selectedDepartment->name) ?> (<?= e($selectedDepartment->type) ?>)</h3>
<?php endif; ?>

<table border="1" width="100%" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th>Номер телефона</th>
            <th>ФИО Абонента</th>
            <th>Помещение</th>
            <th>Подразделение</th>
            <th>Статус</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($phones) > 0): ?>
            <?php foreach ($phones as $phone): ?>
            <tr>
                <td><b><?= e($phone['phone_number'] ?? $phone->phone_number) ?></b></td>
                <td>
                    <?php if (!empty($phone['subscriber'])): ?>
                        <?= e(($phone['subscriber']['last_name'] ?? '') . ' ' . ($phone['subscriber']['first_name'] ?? '')) ?>
                    <?php else: ?>
                        <i>Не прикреплен</i>
                    <?php endif; ?>
                </td>
                <td><?= e($phone['subscriber']['room']['room_name'] ?? '—') ?></td>
                <td><?= e($phone['subscriber']['room']['department']['name'] ?? '—') ?></td>
                <td><?= e($phone['status'] ?? $phone->status) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align: center;">Номеров не найдено</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<br>
<a href="<?= app()->route->getUrl('/departments') ?>">← Назад к подразделениям</a>
