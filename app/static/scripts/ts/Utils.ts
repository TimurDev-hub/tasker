export class Utils {
	static getCookieValue(cookieName: string): string|null {
		const cookie = document.cookie
		.split('; ')
		.find((row) => row.startsWith(cookieName + '='));
		return cookie ? cookie.split('=')[1] : null;
	}

	static renderFormMessage(message: string): void {
		const messageArea = document.getElementById('formMessageArea');
		if (messageArea) messageArea.innerHTML = `<p class="message__text message__text--success">${message}</p>`;
	}

	static renderFormError(message: string): void {
		const messageArea = document.getElementById('formMessageArea');
		if (messageArea) messageArea.innerHTML = `<p class="message__text message__text--error">${message}</p>`;
	}
}