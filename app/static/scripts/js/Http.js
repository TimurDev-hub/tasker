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
    static post(uri, jsonData) {
        return __awaiter(this, void 0, void 0, function* () {
            try {
                const response = yield fetch(uri, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: jsonData
                });
                if (!response.ok)
                    throw new Error(`HTTP error! status: ${response.status}`);
                const responseData = yield response.json();
                return responseData;
            }
            catch (error) {
                console.error('Error sending POST data:', error);
                throw error;
            }
        });
    }
    static delete(uri) {
        return __awaiter(this, void 0, void 0, function* () {
            try {
                const response = yield fetch(uri, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                if (!response.ok)
                    throw new Error(`HTTP error! status: ${response.status}`);
                const responseData = yield response.json();
                return responseData;
            }
            catch (error) {
                console.error('Error sending DELETE data:', error);
                throw error;
            }
        });
    }
}
//# sourceMappingURL=Http.js.map