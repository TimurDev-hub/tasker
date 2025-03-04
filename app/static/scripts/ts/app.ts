import { Http } from "./Http";
import { Templates } from "./Templates";
import { Utils } from "./Utils";

class App {
	static registration(): void {
		try {
			const registrationForm = document.getElementById('registrationForm');

			if (registrationForm) {
				registrationForm.addEventListener('submit', async function(event) {
					event.preventDefault();

					const username = (document.getElementById('username') as HTMLInputElement).value;
					const password = (document.getElementById('password') as HTMLInputElement).value;

					if (!username || !password) throw new Error(`Input data error: ${username} => ${password}`);

					const userData = {
						user_name: username,
						user_password: password
					};

					const jsonData = JSON.stringify(userData);
					const apiAnswer = await Http.post('/api/user', jsonData);

					if (apiAnswer.message) Utils.renderFormMessage(apiAnswer.message);
					if (apiAnswer.error) Utils.renderFormError(apiAnswer.error);
				});
			}

		} catch (error) {
			console.error('Registration error', error);
			throw error;
		}
	}

	static login(): void {
		try {
			const loginForm = document.getElementById('loginForm');

			if (loginForm) {
				loginForm.addEventListener('submit', async function(event) {
					event.preventDefault();

					const username = (document.getElementById('username') as HTMLInputElement).value;
					const password = (document.getElementById('password') as HTMLInputElement).value;

					if (!username || !password) throw new Error(`Input data error: ${username} => ${password}`);

					const loginData = {
						user_name: username,
						user_password: password
					};

					const jsonData = JSON.stringify(loginData);
					const apiAnswer = await Http.post('/api/authentication', jsonData);

					if (apiAnswer.script) App.updateUi();
					if (apiAnswer.error) Utils.renderFormError(apiAnswer.error);
				});
			}

		} catch (error) {
			console.error('Registration error', error);
			throw error;
		}
	}

	static async logout() {
		const apiAnswer = await Http.delete('/api/authentication');
		if (apiAnswer.script) App.updateUi();
	}

	static async deleteAccount(userId: string) {
		const apiAnswer = await Http.delete(`/api/user/${userId}`)
		if (apiAnswer.script !== false) App.updateUi();
	}

	static updateUi() {
		let userId = Utils.getCookieValue('user_id');
		let userName = Utils.getCookieValue('user_name');

		const headerRoot = document.getElementById('header-root');
		const mainRoot = document.getElementById('main-root');

		if (headerRoot === null || mainRoot === null) return;
	
		if (userId === null || userName === null) {
			headerRoot.innerHTML = Templates.renderDefaultHeader();
			mainRoot.innerHTML = Templates.renderLoginForm();
			App.login();

			const loginButton = document.getElementById('loginButton');
			const registrationButton = document.getElementById('registrationButton');

			if (loginButton === null || registrationButton === null) return;
	
			loginButton.addEventListener('click', function() {
				mainRoot.innerHTML = Templates.renderLoginForm();
				App.login();
			});
	
			registrationButton.addEventListener('click', function() {
				mainRoot.innerHTML = Templates.renderRegistrationForm();
				App.registration();
			});

		} else {
			headerRoot.innerHTML = Templates.renderClientHeader(userName);
			mainRoot.innerHTML = Templates.renderTaskCreateForm(userId);

			const logoutButton = document.getElementById('logoutButton');
			const deleteAccountButton = document.getElementById('deleteAccountButton');

			if (logoutButton === null || deleteAccountButton === null) return;

			logoutButton.addEventListener('click', function() {
				App.logout();
			});

			deleteAccountButton.addEventListener('click', function() {
				App.deleteAccount(userId);
			})
		}
	}
}

App.updateUi();