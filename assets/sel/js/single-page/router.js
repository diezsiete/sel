import Router from '../router'
const base = 'http://localhost:8001/pta';
class NapiRouter {
    generate(name, opt_params) {
        return base + Router.generate(name, opt_params);
    }
}
export default new NapiRouter();