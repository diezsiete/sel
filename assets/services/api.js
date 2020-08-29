import fetch from '../utils/fetch';

export default function makeService(endpoint, plural = null) {
    return {
        find(id) {
            return fetch(`${id}`);
        },
        findAll(params) {
            return fetch(plural || endpoint, params);
        },
        create(payload) {
            return fetch(endpoint, {method: 'POST', body: JSON.stringify(payload)});
        },
        del(item) {
            return fetch(item['@id'], {method: 'DELETE'});
        },
        update(payload) {
            return fetch(payload['@id'], {
                method: 'PUT',
                body: JSON.stringify(payload)
            });
        },
        endpoint
    };
}
