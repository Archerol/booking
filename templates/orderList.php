<table id="orderList" class="order-list" data-limit="<?=$limit?>">
	
	<?php if (empty($orderList)): // Список пуст ?>

		<tr class="message">
			<td colspan=4>
				<?php if (isset($first) && $first === true): ?>

					<?=getMessage('order_list_empty')?>

				<?php else: ?>

					<?=getMessage('order_list_empty_page')?>
					<a class="js-orderListPage link" href="#" data-offset="0"><?=getMessage('order_list_try_get_back')?></a>

				<?php endif; ?>
			</td>
		</tr>

	<?php else: // Список содержит элементы ?>

		<tr class="order-list-header">
			<th class="user">Пользователь</th>
			<th class="title">Название</th>
			<th class="decription">Описание</th>
			<th class="price">Цена (комиссия <?=getConfig('orders')['comission']*100?>%)</th>

			<?php if (User_currentUser()['type'] == 'performer'): ?>
				<th class="perform">Выполнить</th>
			<?php endif; ?>

		</tr>

		<?php foreach ($orderList as $i => $order): ?>
			<?php if ($i == $limit) break; ?>
				<tr class="order">
					<td class="user"><?=$order['user']?></td>
					<td class="title"><?=$order['title']?></td>
					<td class="decription"><?=$order['description']?></td>
					<td class="price"><?=$order['price']?> руб.</td>

					<?php if (User_currentUser()['type'] == 'performer'): ?>
						<td class="perform"><button class="js-performOrder" data-id="<?=$order['id']?>">Выполнить</button></td>
					<?php endif; ?>

				</tr>
		<?php endforeach; ?>

	<?php endif; ?>
</table>

<div class="orders-footer">
	<div class="new">
	</div>
	<div class="paging">

		<?php
			// calculates paging buttons
			for ($i=0, $s=0; $s < $ordersCount; $i++, $s=$i*$limit):
				$active = ($offset == $s ? 'active' : ''); 
		?>

			<a href="#" class="js-orderListPage page-link <?=$active?>" data-offset="<?=$s?>"><?=($i+1)?></a>

		<?php endfor; ?>

	</div>
</div>


