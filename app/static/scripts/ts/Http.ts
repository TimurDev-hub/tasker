export class Http {
	static async methodPOST(uri: string, jsonData: string): Promise<any> {
		try {
			const response = await fetch(uri, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: jsonData
			});

			if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

			const responseData = await response.json();
			return responseData;
		
		} catch (error) {
			console.error('Error sending POST data:', error);
			throw error;
		}
	}

	static async methodDELETE(uri: string): Promise<any> {
		try {
			const response = await fetch(uri, {
				method: 'DELETE',
				headers: {
					'Content-Type': 'application/json'
				}
			});

			if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

			const responseData = await response.json();
			return responseData;

		} catch (error) {
			console.error('Error sending DELETE data:', error);
			throw error;
		}
	}
}