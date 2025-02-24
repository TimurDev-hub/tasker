export function getCookie(name) {
	let cookie = document.cookie.split('; ').find(row => row.startsWith(name + '='));
	return cookie ? cookie.split('=')[1] : null;
}

export async function sendData(jsonData, uri) {
	await fetch(uri, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: jsonData
	});
}