<h2>Редактирование: <?= $subscriber->last_name ?></h2>
<form method="post" action="<?= app()->route->getUrl('/subscriber/update') ?>">
    <input type="hidden" name="subscribers_id" value="<?= $subscriber->subscribers_id ?>">
    <input type="text" name="last_name" value="<?= $subscriber->last_name ?>" required>
    <input type="text" name="first_name" value="<?= $subscriber->first_name ?>" required>
    <input type="text" name="middle_name" value="<?= $subscriber->middle_name ?>">
    <input type="date" name="birth_date" value="<?= $subscriber->birth_date ?>" required>
    <input type="number" name="rooms_id" value="<?= $subscriber->rooms_id ?>" required>
    <input type="text" name="phone_number" value="<?= $subscriber->phones->first()->phone_number ?? '' ?>" required>
    <button type="submit">Обновить</button>
</form>
