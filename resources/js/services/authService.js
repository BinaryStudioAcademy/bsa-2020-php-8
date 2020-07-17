
const getToken = (email, password) => {
    return 'Bearer ' + btoa(JSON.stringify({
        email, password
    }));
};

const saveUser = (userData) => {
    localStorage.setItem('user', JSON.stringify(userData));
};

const logout = () => {
    localStorage.setItem('user', '');
};

const getUser = () => {
    const user = localStorage.getItem('user');

    if (!user) {
        throw new Error('user is not authorized');
    }

    return JSON.parse(user);
};

const register =  ({ name, email, password }) => {
    return axios.post('/api/users/', {
        name, email, password
    }, {
        'Content-type': 'application/json'
    }).then(response => {
        const userData = response.data;

        saveUser({
            ...userData,
            password
        });
    });
};

const auth =  ({ email, password }) => {
    return axios.post('/api/auth/', {
        token: getToken(email, password)
    }).then((response) => {
        const userData = response.data;

        saveUser({
            ...userData,
            password
        });
    });
};

export default {
    register,
    getToken,
    getUser,
    logout,
    auth,
};
