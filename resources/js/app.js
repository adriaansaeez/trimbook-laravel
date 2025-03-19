import './bootstrap';
import axios from 'axios';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Configuración global de Axios
axios.defaults.baseURL = '/';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Exportar Axios para que se pueda usar en otros archivos
window.axios = axios;

Alpine.start();
