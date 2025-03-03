import { headerNavbar, headerUserblock, mainLogin, mainRegistration, mainCreate, taskArea } from './templates.js';
import { getCookie, sendDeleteData, sendPostData, renderMessage } from './utils.js';

export function registration() {
	const registrationForm = document.getElementById('registrationForm');
	if (registrationForm) {
		registrationForm.addEventListener('submit', async function(event) {
			event.preventDefault();

			const username = document.getElementById('username').value;
			const password = document.getElementById('password').value;

			const registrationData = {
				user_name: username,
				user_password: password
			};

			const jsonData = JSON.stringify(registrationData);

			const apiAnswer = await sendPostData(jsonData, '/api/user', 'POST');

			if (apiAnswer.message) renderMessage('message__text--success', apiAnswer.message);
			if (apiAnswer.error) renderMessage('message__text--error', apiAnswer.error);
		});
	}
}

export function login() {
	const loginForm = document.getElementById('loginForm');
	if (loginForm) {
		loginForm.addEventListener('submit', async function(event) {
			event.preventDefault();

			const username = document.getElementById('username').value;
			const password = document.getElementById('password').value;

			const loginData = {
				user_name: username,
				user_password: password
			};

			const jsonData = JSON.stringify(loginData);

			const apiAnswer = await sendPostData(jsonData, '/api/authentication');

			if (apiAnswer.script) {
				updateUi()
			} else {
				if (apiAnswer.error) renderMessage('message__text--error', apiAnswer.error);
			};

			//if (apiAnswer.message) renderMessage('message__text--success', apiAnswer.message);
		});
	}
}

export async function logout() {
	const apiAnswer = await sendDeleteData('/api/authentication');

	if (apiAnswer.script) updateUi();

	//if (apiAnswer.message) renderMessage('message__text--success', apiAnswer.message);
	//if (apiAnswer.error) renderMessage('message__text--error', apiAnswer.error);
}

function updateUi() {
	let userId = getCookie('user_id');

	if (userId === null) {
		document.getElementById('header-root').innerHTML = headerNavbar;
		document.getElementById('main-root').innerHTML = mainLogin;
		login();

		document.getElementById('registration-link').addEventListener('click', function() {
			document.getElementById('main-root').innerHTML = mainRegistration;
			registration();
		});

		document.getElementById('login-link').addEventListener('click', function() {
			document.getElementById('main-root').innerHTML = mainLogin;
			login();
		});

	} else {
		document.getElementById('header-root').innerHTML = headerUserblock;
		document.getElementById('main-root').innerHTML = mainCreate + taskArea;
		document.getElementById('logoutButton').addEventListener('click', function() {
			logout();
		});
	}
}

updateUi();