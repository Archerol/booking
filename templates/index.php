<?php showTemplate("header"); ?>

<div class="container">
	<h1 class="h1">Список заказов</h1>

	<div class="block-orders">
		<div class="orders-header">
			<ul class="tab-list">
				<li class="js-orderListTab tab active" data-status="created">Свободные</li>
				<li class="js-orderListTab tab" data-status="done">История</li>
				<?php if (!empty(User_currentUser() && User_currentUser()['type'] == 'customer')): ?>
					<li class="js-createOrder  tab right">Создать заказ</li>
				<?php endif; ?>
			</ul>
		</div>
		
		<div id="orderListContainer" class="order-list-container">
			<?php 
				showTemplate("orderList", [
					'orderList' => $orderList,
					'ordersCount' => $ordersCount,
					'first' => true,
					'offset' => $offset,
					'limit' => $limit,
				]);
			?>
		</div>
		
	</div>
</div>

<?php showTemplate("footer"); ?>