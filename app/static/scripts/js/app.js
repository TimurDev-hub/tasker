import { headerNavbar, mainRegistration, mainLogin} from './templates.js';

function getCookie(name)
{
	const value = `; ${document.cookie}`;
	const parts = value.split(`; ${name}=`);
	if (parts.length === 2) return parts.pop().split(';').shift();
}

const userId = getCookie('user_id');

if (userId == undefined) {
	document.getElementById('header-root').innerHTML = headerNavbar;
	document.getElementById('main-root').innerHTML = mainLogin;

	document.getElementById('registration-link').addEventListener('click', function() {
		document.getElementById('main-root').innerHTML = mainRegistration;
	})

	document.getElementById('login-link').addEventListener('click', function() {
		document.getElementById('main-root').innerHTML = mainLogin;
	})
}
