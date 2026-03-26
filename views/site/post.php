<h1>Список номеров телефонов</h1>

<?php if (count($phones) > 0): ?>
    <ul>
        <?php foreach ($phones as $phone): ?>
            <li>ID: <?= $phone->phones_id ?> | Номер: <?= $phone->phone_number  ?> | Статус: <?= $phone->status ?></li>

        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Номера не найдены.</p>
<?php endif; ?>