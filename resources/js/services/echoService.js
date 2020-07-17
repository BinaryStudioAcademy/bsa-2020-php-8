import Echo from 'laravel-echo'
import requestService from './requestService';

const getEchoInstance = (user) => {
    return new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':8000',
        auth: {
            headers: requestService.getAuthHeader(user)
        }
    });
};

export default {
    getEchoInstance
};
