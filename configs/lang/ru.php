<?php

return [
// Errors

	// Global
	'unknown_error'            => 'Неизвестная ошибка.',
	'database_error'           => 'Ошибка запроса к БД.',
	'ajax_invalid_request'     => 'Неверный запрос. Проверьте правильность пути.',
	'page_not_found'           => 'Страница не найдена.',
	'go_to_index'              => 'Вернуться на главную.',


  // User
	'success_auth'             => 'Успешная авторизация.',
	'success_registration'     => 'Успешная регистрация',
	'success_logout'           => 'Вы вышли из системы.',

	'wrong_login'              => 'Логин содержит недопустимые символы.',
	'login_not_exists'         => 'Логин не существует.',
	'login_already_exists'     => 'Логин уже существует.',
	
	'wrong_confirm_password'   => 'Пароли не совпадают.',
	'wrong_password'           => 'Неверная пара логин-пароль.',
	'unknown_user_type'        => 'Неизвестный тип пользователя.',
	'set_sid_error'            => 'Не удалось сохранить токен авторизации.',

	'wrong_user_type'          => 'Недопустимый тип пользователя.',
	'need_auth'                => 'Необходимо авторизоваться.',
	

	// Orders
	'create_order_wrong_permission'  => 'Нет прав для создания заказа.',
	'create_order_need_auth'         => 'Для создания заказа необходимо авторизоваться.',
	'create_order_empty_title'       => 'Отсутствует название.',
	'create_order_empty_description' => 'Отсутствует описание.',
	'create_order_long_title'        => 'Слишком длинное название.',
	'create_order_long_description'  => 'Слишком длинное описание.',
	'create_order_wrong_price'       => 'Некорректный формат цены.',
	'create_order_success'           => 'Заказ создан.',

	'wrong_order_status'             => 'Недопустимый статус заказа.',
	'order_history_need_auth'        => 'Для просмотра истории необходимо авторизоваться.',
	
	'order_list_empty'               => 'К сожалению, Список заказов пуст.',
	'order_list_empty_page'          => 'Эта страница пуста.',
	'order_list_try_get_back'        => 'Попробуйте вернуться в начало.',

	'unexpected_comission'           => 'Отсутствует комиссия.',
	'perform_order_success'          => 'Заказ выполнен.',
];