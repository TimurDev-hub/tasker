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