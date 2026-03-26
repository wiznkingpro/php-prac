<h1>Справочник: Абоненты и Номера</h1>
<table border="1" style="width:100%; border-collapse: collapse; text-align: left;">
    <tr style="background: #eee;">
        <th>ФИО Абонента</th>
        <th>Список номеров</th>
    </tr>
    <?php foreach ($subscribers as $s): ?>
    <tr>
        <td><strong><?= $s->last_name ?> <?= $s->middle_name ?> <?= $s->first_name ?></strong></td>
        <td>
            <ul style="margin: 0;">
                <?php foreach ($s->phones as $p): ?>
                    <li><?= $p->phone_number ?></li> 
                <?php endforeach; ?>
            </ul>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
