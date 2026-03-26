<h2>Регистрация нового абонента и номера</h2>

<form method="post">
   <input type="text" name="last_name" placeholder="Фамилия" required>
   <input type="text" name="first_name" placeholder="Имя" required>
   <input type="text" name="middle_name" placeholder="Отчество">
   
   <label>Дата рождения:</label>
   <input type="date" name="birth_date" required>

   <label>ID Комнаты:</label>
   <input type="number" name="rooms_id" required>

   <hr>
   <label>Номер телефона:</label>
   <input type="text" name="phone_number" placeholder="Напр: 89991234567" required>
   
   <label>ID Устройства (опционально):</label>
   <input type="number" name="device_id" value="1">

   <button type="submit">
       Создать абонента и закрепить номер
   </button>
</form>