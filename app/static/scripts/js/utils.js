export function getCookie(name) {
	let cookie = document.cookie.split('; ').find(row => row.startsWith(name + '='));
	return cookie ? cookie.split('=')[1] : null;
}

export async function sendPostData(jsonData, uri) {
	const response = await fetch(uri, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: jsonData
	});

	const responseData = await response.json();

	return responseData;
}

export async function sendDeleteData(uri) {
	const response = await fetch(uri, {
		method: 'DELETE',
		headers: {
			'Content-Type': 'application/json'
		}
	});

	const responseData = await response.json();

	return responseData;
}

export function renderMessage(type, content) {
	document.getElementById('message').innerHTML = `<p class="message__text ${type}">${content}</p>`;
}