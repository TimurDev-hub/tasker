import { Http } from "./Http";
import { Templates } from "./Templates";
import { Utils } from "./Utils";

interface Task {
	task_id: number;
	task_title: string;
	task_text: string;
}

interface ApiResponse {
	tasks: Task[];
}

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

					if (apiAnswer.script !== false) App.updateUi();
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

					if (apiAnswer.script !== false) {
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

	static async getTasks(userId: string|number) {
		const taskArea = document.getElementById('tasks-root');
		const apiAnswer = await Http.get(`/api/task/${userId}`) as ApiResponse;

		if (taskArea && apiAnswer.tasks) {
			taskArea.innerHTML = '';

			apiAnswer.tasks.forEach(task => {
				const taskHtml = Templates.renderTask(task.task_title, task.task_text, task.task_id);
				taskArea.innerHTML += taskHtml;
			});
		}

		return apiAnswer.tasks;
	}

	static deleteTask() {
		const deleteButton = document.querySelectorAll('sections__submit--delete');

		deleteButton.forEach(button => {
			button.addEventListener('click', async (event) => {
				event.preventDefault();

				const form = button.closest('sections__form--task');
				const taskIdInput = form?.querySelector('input[name="task_id"]');
				const taskId = (taskIdInput as HTMLInputElement).value;

				const apiAnswer = await Http.delete(`/api/task/${taskId}`);

				if (apiAnswer.message) Utils.renderFormMessage(apiAnswer.message);
				if (apiAnswer.error) Utils.renderFormError(apiAnswer.error);

				if (apiAnswer.script !== false) {
					setTimeout(() => {
						App.updateUi();
					}, 2000);
				}
			});
		});
	}

	static async logout() {
		const apiAnswer = await Http.delete('/api/authentication');
		if (apiAnswer.script !== false) App.updateUi();
	}

	static async deleteAccount(id: string|number) {
		const apiAnswer = await Http.delete(`/api/user/${id}`)
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
			mainRoot.innerHTML = Templates.renderTaskCreateForm() + Templates.renderTaskArea();

			App.createTask(userId);
			App.getTasks(userId).then(tasks => {
				if (tasks.length < 1) {
					const taskArea = document.getElementById('tasks-root');
					if (taskArea) taskArea.innerHTML = Templates.renderTask('Example title', 'Example text', null);
				}
			});
			App.deleteTask();

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