<?php showTemplate("header"); ?>

<div class="container">
	<h1 class="h1">Произошла ошибка</h1>

	<div>
		<?=$errorText;?> <a href="/" class="link"><?=getMessage('go_to_index')?></a>
	</div>
		
	</div>
</div>

<?php showTemplate("footer"); ?>