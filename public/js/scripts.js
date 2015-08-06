function sendAjax(url, args, cbs) 
{
  var defaultCallbacks = {

  	success: function(response) {
  		console.log('Success: ' + response.message);
  	},

  	data: function(response) {
  		console.log('Data: ' + response.data);
  	},

  	error : function (response) { 
  		alert(response.message);
	  },

	  default: function (response) { 
	  	alert('Неизвестный ответ от сервера.');
	  	console.log('Error. Неизвестный ответ от сервера:');
	  	console.log(response);
	  },

	  alert : function (response) { 
	  	alert('Произошла ошибка на сервере.');
	  	console.log('Alert:');
	  	console.log(response);
	  },

	  uploadOnProgress : function () {
	  }

  }

  var callbacks = $.extend({}, defaultCallbacks, cbs);

  $.ajax({
    dataType: "json",
    type: "POST",
    url: "/ajax" + url,
    data: args,
    success: function(response) {
    	if (response.status in callbacks)
    		callbacks[response.status](response) 
    	else
    		callbacks.default(response)		
    },
    error: callbacks.alert		
  })  
}




// Auth

$(document).on('click', '#showLogin, #showRegister', function(e) {

	var speed = 400;
	var open = function($form, callback) {
		$form.slideDown(speed, function() {
			$form.addClass('open');
			if ($.isFunction(callback)) callback();
		})
	}
	var close = function($form, callback) {
		$form.slideUp(speed, function() {
			$form.removeClass('open');
			if ($.isFunction(callback)) callback();
		})
	}

	var curr = this.id;
	var other = (this.id == 'showLogin' ? 'showRegister' : 'showLogin');

	var forms = {
		'showLogin': $('#loginFormContainer'),
		'showRegister': $('#registerFormContainer'),
	}

	if (!forms[curr].hasClass('open') && !forms[other].hasClass('open')) { // both close
		open(forms[curr]);
	} 
	else if (forms[other].hasClass('open')) { // other open
		close(forms[other], function() {
			open(forms[curr])
		});
	} 
	else if (forms[curr].hasClass('open')) { // this open
		close(forms[curr]);
	} else { // both open
		close(forms[curr]);
		close(forms[other]);
	}
})


function ajaxLogin(path, form) {
	return function(e) {
		e.preventDefault();
		var data = form ? $(form).serialize() : null;
		sendAjax(path, data, {
			success: function(response) { 
				location.reload(); 
			}
		});
	}
}

$(document).on('click', '#registerButton', ajaxLogin('/registration', '#registerForm'));

$(document).on('click', '#loginButton',    ajaxLogin('/login', '#loginForm'));

$(document).on('click', '#logoutButton',   ajaxLogin('/logout', null));


