<h2>Редактирование: <?= e($subscriber->last_name) ?></h2>
<form method="post" action="<?= app()->route->getUrl('/subscriber/update') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="subscribers_id" value="<?= (int)$subscriber->subscribers_id ?>">
    <input type="text" name="last_name" value="<?= e($subscriber->last_name) ?>" required>
    <input type="text" name="first_name" value="<?= e($subscriber->first_name) ?>" required>
    <input type="text" name="middle_name" value="<?= e($subscriber->middle_name) ?>">
    <input type="date" name="birth_date" value="<?= e($subscriber->birth_date) ?>" required>
    <input type="number" name="rooms_id" value="<?= (int)$subscriber->rooms_id ?>" required>
    <input type="text" name="phone_number" value="<?= e($subscriber->phones->first()->phone_number ?? '') ?>" required>
    <button type="submit">Обновить</button>
</form>
