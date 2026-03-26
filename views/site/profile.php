<div>
    <h2>Мой профиль</h2>
    <p><strong>ФИО:</strong> <?= $user->last_name ?> <?= $user->first_name ?> <?= $user->middle_name ?></p>

    <p><strong>Департамент:</strong> <?= $user->department->name ?? 'Не указан' ?></p>
    
    <p><strong>Роль:</strong> <?= $user->role ?></p>
    <p><strong>Статус:</strong> <?= $user->status ?></p>
    <hr>
    <a href="<?= app()->route->getUrl('/logout') ?>" style="color: red;">Выйти</a>
</div>