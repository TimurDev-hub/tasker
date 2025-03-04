export class Utils {
    static getCookieValue(cookieName) {
        const cookie = document.cookie
            .split('; ')
            .find((row) => row.startsWith(cookieName + '='));
        return cookie ? cookie.split('=')[1] : null;
    }
    static renderFormMessage(message) {
        const messageArea = document.getElementById('formMessageArea');
        if (messageArea)
            messageArea.innerHTML = `<p class="message__text message__text--success">${message}</p>`;
    }
    static renderFormError(message) {
        const messageArea = document.getElementById('formMessageArea');
        if (messageArea)
            messageArea.innerHTML = `<p class="message__text message__text--error">${message}</p>`;
    }
}
//# sourceMappingURL=Utils.js.map