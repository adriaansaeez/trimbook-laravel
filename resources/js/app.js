import './bootstrap';
import axios from 'axios';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import jQuery from 'jquery';

window.Alpine = Alpine;

// Configuración global de Axios
axios.defaults.baseURL = '/';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Exportar Axios globalmente
window.axios = axios;

// Exportar jQuery globalmente
window.$ = window.jQuery = jQuery;

// Plugin Collapse AlpineJS
Alpine.plugin(collapse);

// Iniciar Alpine
Alpine.start();
