import { headerNavbar, headerUserblock, mainLogin, mainRegistration, mainCreate, taskArea } from './templates.js';
import { getCookie } from './utils.js';
import { setupRegistrationForm, setupLoginForm } from './functions.js';

const userId = getCookie('user_id');


if (!userId === null) {
	document.getElementById('header-root').innerHTML = headerNavbar;
	//document.getElementById('main-root').innerHTML = mainLogin;

	document.getElementById('registration-link').addEventListener('click', function() {
		document.getElementById('main-root').innerHTML = mainRegistration;
		setupRegistrationForm();
	});

	document.getElementById('login-link').addEventListener('click', function() {
		document.getElementById('main-root').innerHTML = mainLogin;
		setupLoginForm();
	});

} else {
	document.getElementById('header-root').innerHTML = headerUserblock;
	document.getElementById('main-root').innerHTML = mainCreate + taskArea;
}
