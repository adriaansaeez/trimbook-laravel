import './bootstrap';
import axios from 'axios';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import jQuery from 'jquery';
import Pikaday from 'pikaday';
import 'pikaday/css/pikaday.css';


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
