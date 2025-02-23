import { headerNavbar, mainRegistration, mainLogin } from './templates.js';
import { getCookie } from './functions.js';

const userId = getCookie('user_id');

if (userId === null) {
	document.getElementById('header-root').innerHTML = headerNavbar;
	document.getElementById('main-root').innerHTML = mainLogin;

	document.getElementById('registration-link').addEventListener('click', function() {
		document.getElementById('main-root').innerHTML = mainRegistration;
	});

	document.getElementById('login-link').addEventListener('click', function() {
		document.getElementById('main-root').innerHTML = mainLogin;
	});
}