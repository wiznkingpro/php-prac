<h2>Регистрация нового абонента и номера</h2>

<form method="post">
   <?= csrf_field() ?>
   <div>
       <label>Фамилия:</label><br>
       <input type="text" name="last_name" placeholder="Фамилия" required>
   </div>
   <div>
       <label>Имя:</label><br>
       <input type="text" name="first_name" placeholder="Имя" required>
   </div>
   <div>
       <label>Отчество:</label><br>
       <input type="text" name="middle_name" placeholder="Отчество">
   </div>
   <div>
       <label>Дата рождения:</label><br>
       <input type="date" name="birth_date" required>
   </div>
   <div>
       <label>Помещение:</label><br>
       <select name="rooms_id" required>
           <option value="" disabled selected>-- Выберите помещение --</option>
           <?php if (!empty($rooms)): ?>
               <?php foreach ($rooms as $room): ?>
                   <option value="<?= (int)$room->rooms_id ?>">
                       <?= e($room->room_name) ?> (<?= e($room->room_type) ?>, <?= e($room->department->name ?? '—') ?>)
                   </option>
               <?php endforeach; ?>
           <?php endif; ?>
       </select>
   </div>
   <hr>
   <div>
       <label>Номер телефона:</label><br>
       <input type="text" name="phone_number" placeholder="Напр: 89991234567" required>
   </div>
   <br>
   <button type="submit">Создать абонента и закрепить номер</button>
</form>
