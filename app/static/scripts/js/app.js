var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import { Http } from "./Http";
import { Templates } from "./Templates";
import { Utils } from "./Utils";
class App {
    static registration() {
        try {
            const registrationForm = document.getElementById('registrationForm');
            if (registrationForm) {
                registrationForm.addEventListener('submit', function (event) {
                    return __awaiter(this, void 0, void 0, function* () {
                        event.preventDefault();
                        const username = document.getElementById('username').value;
                        const password = document.getElementById('password').value;
                        if (!username || !password)
                            throw new Error(`Input data error: ${username} => ${password}`);
                        const userData = {
                            user_name: username,
                            user_password: password
                        };
                        const jsonData = JSON.stringify(userData);
                        const apiAnswer = yield Http.post('/api/user', jsonData);
                        if (apiAnswer.message)
                            Utils.renderFormMessage(apiAnswer.message);
                        else if (apiAnswer.error)
                            Utils.renderFormError(apiAnswer.error);
                    });
                });
            }
        }
        catch (error) {
            console.error('Registration error', error);
            throw error;
        }
    }
    static login() {
        try {
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function (event) {
                    return __awaiter(this, void 0, void 0, function* () {
                        event.preventDefault();
                        const username = document.getElementById('username').value;
                        const password = document.getElementById('password').value;
                        if (!username || !password)
                            throw new Error(`Input data error: ${username} => ${password}`);
                        const loginData = {
                            user_name: username,
                            user_password: password
                        };
                        const jsonData = JSON.stringify(loginData);
                        const apiAnswer = yield Http.post('/api/authentication', jsonData);
                        if (apiAnswer.script)
                            App.updateUi();
                        if (apiAnswer.error)
                            Utils.renderFormError(apiAnswer.error);
                    });
                });
            }
        }
        catch (error) {
            console.error('Registration error', error);
            throw error;
        }
    }
    static logout() {
        return __awaiter(this, void 0, void 0, function* () {
            const apiAnswer = yield Http.delete('/api/authentication');
            if (apiAnswer.script)
                App.updateUi();
        });
    }
    static updateUi() {
        let userId = Utils.getCookieValue('user_id');
        let userName = Utils.getCookieValue('user_name');
        const headerRoot = document.getElementById('header-root');
        const mainRoot = document.getElementById('main-root');
        if (headerRoot === null || mainRoot === null)
            return;
        if (userId === null) {
            headerRoot.innerHTML = Templates.renderDefaultHeader();
            mainRoot.innerHTML = Templates.renderLoginForm();
            App.login();
            const loginButton = document.getElementById('registration-link');
            const registrationButton = document.getElementById('registrationButton');
            if (loginButton === null || registrationButton === null)
                return;
            loginButton.addEventListener('click', function () {
                mainRoot.innerHTML = Templates.renderRegistrationForm();
                App.registration();
            });
            registrationButton.addEventListener('click', function () {
                mainRoot.innerHTML = Templates.renderLoginForm();
                App.login();
            });
        }
        else {
            if (userName === null)
                return;
            headerRoot.innerHTML = Templates.renderClientHeader(userName);
            mainRoot.innerHTML = Templates.renderTaskCreateForm('null');
            const logoutButton = document.getElementById('logoutButton');
            const deleteAccountButton = document.getElementById('deleteAccountButton');
            if (logoutButton === null || deleteAccountButton === null)
                return;
            logoutButton.addEventListener('click', function () {
                App.logout();
            });
        }
    }
}
//# sourceMappingURL=App.js.map