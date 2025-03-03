// HTML templates
import { getCookie } from "./utils.js";

const userName = getCookie('user_name');

export const headerNavbar = `
	<nav class="header__nav">
		<ul class="header__menu">
			<li class="header__menu-item">
				<button id="login-link" class="header__menu-link header__menu-link--blue">Log in</button>
			</li>
			<li class="header__menu-item">
				<button id="registration-link" class="header__menu-link header__menu-link--yellow">Registration</button>
			</li>
		</ul>
	</nav>
`;

export const headerUserblock = `
	<div class="header__user-block">
		<ul class="header__menu">
			<li class="header__menu-item">
				<p class="header__menu-link header__menu-link--account">${userName}</p>
			</li>
			<li class="header__menu-item">
				<button id="logoutButton" class="header__menu-link header__menu-link--yellow">Log out</button>
			</li>
			<li class="header__menu-item">
				<button class="header__menu-link header__menu-link--yellow">Delete</button>
			</li>
		</ul>
	</div>
`;

export const mainRegistration = `
	<section class="sections sections--userforms">
		<legend class="sections__header sections__header--userforms">Registration</legend>
		<form id="registrationForm" class="sections__form">
			<fieldset class="sections__field sections__field--userforms">
				<label for="username" class="sections__title">Name</label>
				<input id="username" name="user_name" type="text" class="sections__text sections__text--userforms main__inputs" placeholder="Think of your own name!" maxlength="12" required>
			</fieldset>
			<fieldset class="sections__field">
				<label for="password" class="sections__title">Password</label>
				<input id="password" name="user_password" type="password" class="sections__text sections__text--userforms main__inputs" placeholder="...and a strong password!" maxlength="24" required>
			</fieldset>
			<button type="submit" class="sections__submit">Submit</button>
			<span id="message"></span>
		</form>
	</section>
`;

export const mainLogin = `
	<section class="sections sections--userforms">
		<legend class="sections__header sections__header--userforms">Login</legend>
		<form id="loginForm" class="sections__form">
			<fieldset class="sections__field sections__field--userforms">
				<label for="username" class="sections__title">Name</label>
				<input id="username" name="user_name" type="text" class="sections__text sections__text--userforms main__inputs" placeholder="Your username is..." maxlength="16" required>
			</fieldset>
			<fieldset class="sections__field">
				<label for="password" class="sections__title">Password</label>
				<input id="password" name="user_password" type="password" class="sections__text sections__text--userforms main__inputs" placeholder="Your password is..." maxlength="24" required>
			</fieldset>
			<button type="submit" class="sections__submit">Submit</button>
			<span id="message"></span>
		</form>
	</section>
`;

export const mainCreate = `
	<section class="sections">
		<legend class="sections__header">Add new task</legend>
		<form id="createForm" class="sections__form">
			<fieldset class="sections__field">
				<label for="title" class="sections__title">Title</label>
				<input id="title" name="task_title" type="text" class="sections__text main__inputs" placeholder="Your task\`s title is..." maxlength="32" required>
			</fieldset>
			<fieldset class="sections__field">
				<label for="task" class="sections__title">Task</label>
				<textarea id="task" name="task_text" class="sections__text sections__text--task main__inputs" placeholder="Write what you want!" maxlength="128" required></textarea>
			</fieldset>
			<input name="user_id" type="number" value="" hidden>
			<button type="submit" class="sections__submit">Submit</button>
		</form>
	</section>
`;

export const taskArea = `
	<section class="sections">
		<legend class="sections__header">Tasks list</legend>
		<div id="tasks-root" class="sections__tasks-list">

		</div>
	</section>
`;

export const taskTemplate = `
	<div class="sections__task">
		<form class="sections__form sections__form--task">
			<fieldset class="sections__field">
				<label class="sections__title">Title</label>
				<input name="task_title" type="text" class="sections__text main__inputs" value="___" disabled>
			</fieldset>
			<fieldset class="sections__field">
				<label class="sections__title">Task</label>
				<textarea name="task_text" class="sections__text sections__text--task main__inputs" disabled>___</textarea>
			</fieldset>
			<input name="task_id" type="number" value="___" hidden>
			<button type="submit" class="sections__submit sections__submit--delete">Delete</button>
		</form>
	</div>
`;