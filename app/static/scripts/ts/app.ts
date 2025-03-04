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

					const userData = {
						user_name: username,
						user_password: password
					};

					const jsonData = JSON.stringify(userData);
					const apiAnswer = await Http.post('/api/authentication', jsonData);

					if (apiAnswer.script) App.updateUi();
					if (apiAnswer.error) Utils.renderFormError(apiAnswer.error);
				});
			}

		} catch (error) {
			throw error;
		}
	}

	static createTask(userId: string|number) {
		try {
			const taskCreateForm = document.getElementById('taskCreateForm');

			if (taskCreateForm) {
				taskCreateForm.addEventListener('submit', async function(event) {
					event.preventDefault();

					const title = (document.getElementById('title') as HTMLInputElement).value;
					const text = (document.getElementById('task') as HTMLInputElement).value;
					const id = userId;

					const taskData = {
						task_title: title,
						task_text: text,
						user_id: id
					};

					const jsonData = JSON.stringify(taskData);
					const apiAnswer = await Http.post('/api/task', jsonData);

					if (apiAnswer.message) Utils.renderFormMessage(apiAnswer.message);
					if (apiAnswer.error) Utils.renderFormError(apiAnswer.error);

					if (apiAnswer.script) {
						setTimeout(() => {
							App.updateUi();
						}, 2000);
					}
				});
			}

		} catch (error) {
			throw error;
		}
	}

	static async logout() {
		const apiAnswer = await Http.delete('/api/authentication');
		if (apiAnswer.script) App.updateUi();
	}

	static async deleteAccount(id: string|number) {
		const apiAnswer = await Http.delete(`/api/user/${id}`)
		if (apiAnswer.script) App.updateUi();
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
			App.createTask(userId);

			const logoutButton = document.getElementById('logoutButton');
			const deleteAccountButton = document.getElementById('deleteAccountButton');

			if (logoutButton === null || deleteAccountButton === null) return;

			logoutButton.addEventListener('click', function() {
				App.logout();
			});

			deleteAccountButton.addEventListener('click', function() {
				App.deleteAccount(userId);
			});
		}
	}
}

App.updateUi();