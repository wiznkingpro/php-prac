<h2>Статистика и подсчет абонентов</h2>

<div style="display: flex; gap: 40px;">
    <section style="flex: 1;">
        <h3>Количество абонентов по подразделениям</h3>
        <table border="1" width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Подразделение</th>
                    <th>Количество абонентов</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($byDepartment as $deptName => $count): ?>
                <tr>
                    <td><?= e($deptName) ?></td>
                    <td><b><?= (int)$count ?></b></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section style="flex: 1;">
        <h3>Количество абонентов по помещениям</h3>
        <table border="1" width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Помещение</th>
                    <th>Количество абонентов</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($byRoom as $roomName => $count): ?>
                <tr>
                    <td><?= e($roomName) ?></td>
                    <td><b><?= (int)$count ?></b></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>
