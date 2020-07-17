import authService from './authService';
import axios from 'axios';

const getAuthHeader = (user) => {
    const token = authService.getToken(user.email, user.password);

    return {
        'WWW-Authenticate': `${token}`
    };
};

const auth = (request) => {
    const user = authService.getUser();

    return request(getAuthHeader(user));
};

const getUrl = (path) => window.API_URL + path;

const post = (url, data, headers = {}) => auth((authHeader) => axios.post(getUrl(url), data, {
    headers: {
        'Content-type': 'application/json',
        ...headers,
        ...authHeader
    }
}));

const get = (url, params) => auth((headers) => axios.get(getUrl(url), { params, headers }));

const requestService = {
    getAuthHeader,
    post,
    get,
};

export default requestService;