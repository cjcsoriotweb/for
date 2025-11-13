import './bootstrap';
import '../css/app.css';

// ✅ Alpine local
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// ✅ Intro.js local (JS + CSS)
import introJs from 'intro.js';
import 'intro.js/minified/introjs.min.css';

window.introJs = introJs;

// ✅ Axios local
import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';