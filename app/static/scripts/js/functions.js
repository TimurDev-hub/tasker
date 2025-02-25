export function getCookie(name) {
	let cookie = document.cookie.split('; ').find(row => row.startsWith(name + '='));
	return cookie ? cookie.split('=')[1] : null;
}

export async function sendData(jsonData, uri, method) {
	const response = await fetch(uri, {
		method: method,
		headers: {
			'Content-Type': 'application/json'
		},
		body: jsonData
	});

	const responseData = await response.json();

	return responseData;
}

export function renderMessage(type, content) {
	document.getElementById('message').innerHTML = `<p class="message__text ${type}">${content}</p>`;
}

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

			renderMessage('message__text--yes', apiAnswer.message);
		});
	}
}