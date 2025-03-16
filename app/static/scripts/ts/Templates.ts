export class Templates {
	static renderDefaultHeader(): string {
		return `
			<nav class="header__nav">
				<ul class="header__menu">
					<li class="header__menu-item">
						<button id="loginButton" class="header__menu-link header__menu-link--blue">Log in</button>
					</li>
					<li class="header__menu-item">
						<button id="registrationButton" class="header__menu-link header__menu-link--yellow">Registration</button>
					</li>
				</ul>
			</nav>
		`;
	}

	static renderClientHeader(username: string): string {
		return `
			<nav class="header__nav">
				<ul class="header__menu">
					<li class="header__menu-item">
						<p class="header__menu-link header__menu-link--account">${username}</p>
					</li>
					<li class="header__menu-item">
						<button id="logoutButton" class="header__menu-link header__menu-link--yellow">Log out</button>
					</li>
					<li class="header__menu-item">
						<button id="deleteAccountButton" class="header__menu-link header__menu-link--yellow">Delete</button>
					</li>
				</ul>
			</nav>
		`;
	}

	static renderLoginForm(): string {
		return `
			<section class="sections sections--userforms">
				<legend class="sections__header sections__header--userforms">Login</legend>
				<form id="loginForm" class="sections__form">
					<fieldset class="sections__field sections__field--userforms">
						<label for="username" class="sections__title">Name</label>
						<input id="username" name="username" name="user_name" type="text" class="sections__text sections__text--userforms main__inputs" placeholder="Your username is..." maxlength="16" required>
					</fieldset>
					<fieldset class="sections__field">
						<label for="password" class="sections__title">Password</label>
						<input id="password" name="password" name="user_password" type="password" class="sections__text sections__text--userforms main__inputs" placeholder="Your password is..." maxlength="24" required>
					</fieldset>
					<button type="submit" class="sections__submit">Submit</button>
					<span id="formMessageArea"></span>
				</form>
			</section>
		`;
	}

	static renderRegistrationForm(): string {
		return `
			<section class="sections sections--userforms">
				<legend class="sections__header sections__header--userforms">Registration</legend>
				<form id="registrationForm" class="sections__form">
					<fieldset class="sections__field sections__field--userforms">
						<label for="username" class="sections__title">Name</label>
						<input id="username" name="username" name="user_name" type="text" class="sections__text sections__text--userforms main__inputs" placeholder="Think of your own name!" maxlength="12" required>
					</fieldset>
					<fieldset class="sections__field">
						<label for="password" class="sections__title">Password</label>
						<input id="password" name="password" name="user_password" type="password" class="sections__text sections__text--userforms main__inputs" placeholder="...and a strong password!" maxlength="24" required>
					</fieldset>
					<button type="submit" class="sections__submit">Submit</button>
					<span id="formMessageArea"></span>
				</form>
			</section>
		`;
	}

	static renderTaskCreateForm(): string {
		return `
			<section class="sections">
				<legend class="sections__header">Add new task</legend>
				<form id="taskCreateForm" class="sections__form">
					<fieldset class="sections__field">
						<label for="title" class="sections__title">Title</label>
						<input id="title" name="task_title" type="text" class="sections__text main__inputs" placeholder="Your task\`s title is..." maxlength="16" required>
					</fieldset>
					<fieldset class="sections__field">
						<label for="task" class="sections__title">Task</label>
						<textarea id="task" name="task_text" class="sections__text sections__text--task main__inputs" placeholder="Write what you want!" maxlength="64" required></textarea>
					</fieldset>
					<button type="submit" class="sections__submit">Submit</button>
					<span id="formMessageArea"></span>
				</form>
			</section>
		`;
	}

	static renderTaskArea(): string {
		return `
			<section class="sections">
				<legend class="sections__header">Tasks list</legend>
				<div id="tasks-root" class="sections__tasks-list">

				</div>
			</section>
		`;
	}

	static renderTask(taskTitle: string, taskText: string, taskId: number|null): string {
		return `
			<div class="sections__task">
				<form class="sections__form sections__form--task">
					<fieldset class="sections__field">
						<label for="title${taskId}" class="sections__title">Title</label>
						<input id="title${taskId}" name="task_title" type="text" class="sections__text main__inputs" value="${taskTitle}" disabled>
					</fieldset>
					<fieldset class="sections__field">
						<label for="text${taskId}" class="sections__title">Task</label>
						<textarea id="text${taskId}" name="task_text" class="sections__text sections__text--task main__inputs" disabled>${taskText}</textarea>
					</fieldset>
					<input class="task_id" name="task_id" type="number" value="${taskId}" hidden>
					<button type="submit" class="sections__submit sections__submit--delete">Delete</button>
				</form>
			</div>
		`;
	}
}