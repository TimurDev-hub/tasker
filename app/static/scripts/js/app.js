import { headerNavbar, mainRegistration, mainLogin} from './templates.js';

function getCookie(name)
{
	const value = `; ${document.cookie}`;
	const parts = value.split(`; ${name}=`);
	if (parts.length === 2) return parts.pop().split(';').shift();
}

const header = document.getElementById('header-root');
const main = document.getElementById('main-root');
const userId = getCookie('user_id');

if (userId == undefined) {
	header.innerHTML = headerNavbar;
	main.innerHTML = mainLogin;

	document.getElementById('registration-link').addEventListener('click', function() {
		main.innerHTML = mainRegistration;
	});

	document.getElementById('login-link').addEventListener('click', function() {
		main.innerHTML = mainLogin;
	});
}
