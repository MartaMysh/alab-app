import axios from 'axios';

const apiClient = axios.create({
    baseURL: 'http://localhost:8088/api',
});


apiClient.interceptors.request.use((config) => {
    const token = localStorage.getItem('jwt_token');
    if (token && config.headers) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default apiClient;
