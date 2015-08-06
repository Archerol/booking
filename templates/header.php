<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="keywords" content="">		
		<title>Orders</title>

		<link href='//fonts.googleapis.com/css?family=Roboto:400,500,700,300,400italic&subset=latin,cyrillic,cyrillic-ext' rel='stylesheet' type='text/css'>
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		
		<link rel="stylesheet" type="text/css" href="/css/styles.css">

	</head>

	<body>

		<div class="block-navbar">
			<div class="container clearfix">
				<a href="/" class="logo">Orders</a>
				<ul class="menu">
				<?php if (!empty(User_currentUser())): ?>
					<li class="menu-item disabled">

						<?=User_currentUser()['login']?>

						<?php if (User_currentUser()['type'] == 'performer'): ?>

							(<span id="userMoney"><?=User_currentUser()['account']?></span> руб.)

						<?php endif; ?>

					</li>
					<li id="logoutButton" class="menu-item">
						<span>Выход</span>
					</li>
				<?php else: ?>
					<li id="showLogin" class="menu-item">
						<span>Вход</span>
					</li>
					<div id="loginFormContainer" class="login-container">
						<form id="loginForm" class="login-form">
							<div>
								<label>Логин</label>
								<input name="login" type="text">
							</div>
							<div>
								<label>Пароль</label>
								<input name="password" type="password">
							</div>
							<div class="button-container">
								<button id="loginButton">Войти</button>
							</div>
						</form>
					</div>
					<li id="showRegister" class="menu-item">
						<span>Регистрация</span>
					</li>
					<div id="registerFormContainer" class="login-container">
						<form id="registerForm" class="login-form">
							<div>
								<label>Логин</label>
								<input name="login" type="text">
							</div>
							<div>
								<label>Пароль</label>
								<input name="password" type="password">
							</div>
							<div>
								<label>Повторите пароль</label>
								<input name="confirm" type="password">
							</div>
							<div class="type-container">
								<label>Тип пользователя:</label>
								<select name="type">
									<option value="customer">Заказчик</option>
									<option value="performer">Исполнитель</option>
								</select>
							</div>
							<div class="button-container">
								<button id="registerButton">Зарегистрироваться</button>
							</div>
						</form>
					</div>
				<?php endif; ?>

				</ul>
			</div>
		</div>			




