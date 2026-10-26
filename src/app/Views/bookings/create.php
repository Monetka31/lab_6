<?php
// Шаблон формы создания бронирования.
// Переменные: $workspaces (список рабочих мест для выбора), $errors (ошибки проверки), $old (прежние значения полей)
?>
<h1>Новое бронирование</h1>

<!-- action — адрес, на который браузер отправит данные; method="post" — способом POST -->
<form action="/bookings" method="post" class="card form">

  <label for="employee_name">ФИО сотрудника</label>
  <input id="employee_name" type="text" name="employee_name" value="<?= e($old['employee_name'] ?? '') ?>">
  <?php if (isset($errors['employee_name'])): ?><div class="error"><?= e($errors['employee_name']) ?></div><?php endif; ?>

  <label for="workspace_id">Рабочее место</label>
  <select id="workspace_id" name="workspace_id">
    <option value="">Выберите рабочее место…</option>
    <?php foreach ($workspaces as $workspace): ?>
      <option value="<?= e($workspace['id']) ?>" <?= ((int) ($old['workspace_id'] ?? 0)) === $workspace['id'] ? 'selected' : '' ?>>
        <?= e($workspace['title']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <?php if (isset($errors['workspace_id'])): ?><div class="error"><?= e($errors['workspace_id']) ?></div><?php endif; ?>

  <label for="time_slot">Время</label>
  <select id="time_slot" name="time_slot">
    <?php foreach (['morning' => 'Утро', 'afternoon' => 'День', 'full_day' => 'Весь день'] as $code => $name): ?>
      <option value="<?= e($code) ?>" <?= ($old['time_slot'] ?? 'full_day') === $code ? 'selected' : '' ?>><?= e($name) ?></option>
    <?php endforeach; ?>
  </select>
  <?php if (isset($errors['time_slot'])): ?><div class="error"><?= e($errors['time_slot']) ?></div><?php endif; ?>

  <label for="booking_date">Дата бронирования</label>
  <input id="booking_date" type="date" name="booking_date" value="<?= e($old['booking_date'] ?? '') ?>">
  <?php if (isset($errors['booking_date'])): ?><div class="error"><?= e($errors['booking_date']) ?></div><?php endif; ?>

  <p><button type="submit" class="btn">Забронировать</button> <a href="/bookings">Отмена</a></p>
</form>
