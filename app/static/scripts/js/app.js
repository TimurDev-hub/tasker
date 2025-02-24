import { headerNavbar, mainRegistration, mainLogin } from './templates.js';
import { getCookie, sendData } from './functions.js';

const userId = getCookie('user_id');

/*
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
*/

document.getElementById('header-root').innerHTML = headerNavbar;
document.getElementById('main-root').innerHTML = mainRegistration;

document.addEventListener('DOMContentLoaded', function() {
	if (document.getElementById('registrationForm')) {
		document.getElementById('registrationForm').addEventListener('submit', async function(event) {
			event.preventDefault();

			const username = document.getElementById('username').value;
			const password = document.getElementById('password').value;

			const registrationData = {
				user_name: username,
				user_password: password
			};

			const jsonData = JSON.stringify(registrationData);

			const apiAnswer = await sendData(jsonData, '/user', 'POST');
		});
	}
});