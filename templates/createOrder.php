<?php showTemplate("header"); ?>

<div class="container">
	<h1 class="h1">Создание заказа</h1>

	<?php if (empty(User_currentUser())): ?>
		<?=getMessage('create_order_need_auth')?>
	<?php else: ?>
		<div>
			<form id="createOrderFrom" class="create-order">
				<div class="form-group">
					<label>Название (максимум <?=getConfig('orders')['title_max_length']?> символов):</label>
					<input class="input-title" name="title" type="text">
				</div>

				<div class="form-group">
					<label>Описание (максимум <?=getConfig('orders')['descr_max_length']?> символов):</label>
					<textarea class="input-description" name="description"></textarea>
				</div>

				<div class="form-group small">
					<label>Цена (в рублях):</label>
					<input name="price" type="text">
				</div>
				
				<div class="form-group small clearfix">
					<button id="createOrderButton" class="pull-left">Создать</button>
					<button id="cancelOrderButton" class="pull-right">Отмена</button>
				</div>
			</form>
		</div>
	<?php endif; ?>
</div>

<?php showTemplate("footer"); ?>