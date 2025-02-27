import { sendData, renderMessage } from "./utils.js";

export function setupRegistrationForm() {
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

			const apiAnswer = await sendData(jsonData, '/user', 'POST');

			if (apiAnswer.message) renderMessage('message__text--success', apiAnswer.message);
			if (apiAnswer.error) renderMessage('message__text--error', apiAnswer.error);
		});
	}
}

export function setupLoginForm() {
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

			const apiAnswer = await sendData(jsonData, '/authentication', 'POST');

			if (apiAnswer.message) renderMessage('message__text--success', apiAnswer.message);
			if (apiAnswer.error) renderMessage('message__text--error', apiAnswer.error);
		});
	}
}