var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
export class Http {
    static get(uri) {
        return __awaiter(this, void 0, void 0, function* () {
            const response = yield fetch(uri, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            const responseData = yield response.json();
            return responseData;
        });
    }
    static post(uri, jsonData) {
        return __awaiter(this, void 0, void 0, function* () {
            const response = yield fetch(uri, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: jsonData
            });
            const responseData = yield response.json();
            return responseData;
        });
    }
    static delete(uri) {
        return __awaiter(this, void 0, void 0, function* () {
            const response = yield fetch(uri, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            const responseData = yield response.json();
            return responseData;
        });
    }
}
//# sourceMappingURL=Http.js.map