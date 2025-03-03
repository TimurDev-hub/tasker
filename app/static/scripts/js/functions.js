import { sendPostData, sendDeleteData, renderMessage } from "./utils.js";
//import { updateUi } from "./app.js";

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

			if (apiAnswer.script) updateUi();

			//if (apiAnswer.message) renderMessage('message__text--success', apiAnswer.message);
			//if (apiAnswer.error) renderMessage('message__text--error', apiAnswer.error);
		});
	}
}

export async function logout() {
	const apiAnswer = await sendDeleteData('/api/authentication');

	if (apiAnswer.script) updateUi();

	//if (apiAnswer.message) renderMessage('message__text--success', apiAnswer.message);
	//if (apiAnswer.error) renderMessage('message__text--error', apiAnswer.error);
}