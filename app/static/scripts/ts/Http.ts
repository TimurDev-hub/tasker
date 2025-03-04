export class Http {
	static async get(uri: string): Promise<any> {
		const response = await fetch(uri, {
			method: 'GET',
			headers: {
				'Content-Type': 'application/json'
			}
		});

		const responseData = await response.json();
		return responseData;
	}

	static async post(uri: string, jsonData: string): Promise<any> {
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

	static async delete(uri: string): Promise<any> {
		const response = await fetch(uri, {
			method: 'DELETE',
			headers: {
				'Content-Type': 'application/json'
			}
		});

		const responseData = await response.json();
		return responseData;
	}
}