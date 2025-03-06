var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import { Http } from "./Http.js";
import { Templates } from "./Templates.js";
import { Utils } from "./Utils.js";
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
                        const userData = {
                            user_name: username,
                            user_password: password
                        };
                        const jsonData = JSON.stringify(userData);
                        const apiAnswer = yield Http.post('/api/user', jsonData);
                        if (apiAnswer.message)
                            Utils.renderFormMessage(apiAnswer.message);
                        if (apiAnswer.error)
                            Utils.renderFormError(apiAnswer.error);
                    });
                });
            }
        }
        catch (error) {
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
                        const userData = {
                            user_name: username,
                            user_password: password
                        };
                        const jsonData = JSON.stringify(userData);
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
            throw error;
        }
    }
    static createTask(userId) {
        try {
            const taskCreateForm = document.getElementById('taskCreateForm');
            if (taskCreateForm) {
                taskCreateForm.addEventListener('submit', function (event) {
                    return __awaiter(this, void 0, void 0, function* () {
                        event.preventDefault();
                        const title = document.getElementById('title').value;
                        const text = document.getElementById('task').value;
                        const id = userId;
                        const taskData = {
                            task_title: title,
                            task_text: text,
                            user_id: id
                        };
                        const jsonData = JSON.stringify(taskData);
                        const apiAnswer = yield Http.post('/api/task', jsonData);
                        if (apiAnswer.message)
                            Utils.renderFormMessage(apiAnswer.message);
                        if (apiAnswer.error)
                            Utils.renderFormError(apiAnswer.error);
                        if (apiAnswer.script) {
                            setTimeout(() => {
                                App.updateUi();
                            }, 2000);
                        }
                    });
                });
            }
        }
        catch (error) {
            throw error;
        }
    }
    static getTasks(userId) {
        return __awaiter(this, void 0, void 0, function* () {
            const taskArea = document.getElementById('tasks-root');
            const apiAnswer = yield Http.get(`/api/task/${userId}`);
            if (taskArea && apiAnswer.tasks) {
                taskArea.innerHTML = '';
                apiAnswer.tasks.forEach(task => {
                    const taskHtml = Templates.renderTask(task.task_title, task.task_text, task.task_id);
                    taskArea.innerHTML += taskHtml;
                });
            }
            return apiAnswer.tasks;
        });
    }
    static logout() {
        return __awaiter(this, void 0, void 0, function* () {
            const apiAnswer = yield Http.delete('/api/authentication');
            if (apiAnswer.script)
                App.updateUi();
        });
    }
    static deleteAccount(id) {
        return __awaiter(this, void 0, void 0, function* () {
            const apiAnswer = yield Http.delete(`/api/user/${id}`);
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
        if (userId === null || userName === null) {
            headerRoot.innerHTML = Templates.renderDefaultHeader();
            mainRoot.innerHTML = Templates.renderLoginForm();
            App.login();
            const loginButton = document.getElementById('loginButton');
            const registrationButton = document.getElementById('registrationButton');
            if (loginButton === null || registrationButton === null)
                return;
            loginButton.addEventListener('click', function () {
                mainRoot.innerHTML = Templates.renderLoginForm();
                App.login();
            });
            registrationButton.addEventListener('click', function () {
                mainRoot.innerHTML = Templates.renderRegistrationForm();
                App.registration();
            });
        }
        else {
            headerRoot.innerHTML = Templates.renderClientHeader(userName);
            mainRoot.innerHTML = Templates.renderTaskCreateForm() + Templates.renderTaskArea();
            App.createTask(userId);
            App.getTasks(userId).then(tasks => {
                if (tasks.length < 1) {
                    const taskArea = document.getElementById('tasks-root');
                    if (taskArea)
                        taskArea.innerHTML = Templates.renderTask('Example title', 'Example tetx', null);
                }
            });
            const logoutButton = document.getElementById('logoutButton');
            const deleteAccountButton = document.getElementById('deleteAccountButton');
            if (logoutButton === null || deleteAccountButton === null)
                return;
            logoutButton.addEventListener('click', function () {
                App.logout();
            });
            deleteAccountButton.addEventListener('click', function () {
                App.deleteAccount(userId);
            });
        }
    }
}
App.updateUi();
//# sourceMappingURL=App.js.map