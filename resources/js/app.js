import './bootstrap';
import '../css/app.css';


// ✅ Axios local
import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
