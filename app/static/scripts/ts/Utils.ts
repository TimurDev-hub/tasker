export class Utils {
	static getCookieValue(cookieName: string): string|null {
		const cookie = document.cookie
		.split('; ')
		.find((row) => row.startsWith(cookieName + '='));
		return cookie ? cookie.split('=')[1] : null;
	}

	static renderFormMessage(type: string, message: string): void {
		const messageArea = document.getElementById('formMessageArea');
		if (messageArea) messageArea.innerHTML = `<p class="message__text ${type}">${message}</p>`;
	}
}