import Framework7 from './framework7-custom.js';

import '../css/framework7-custom.less';
import '../css/icons.css';
import '../css/app.less';

import routes from './routes.js';
import store from './store.js';
import App from '../app.f7.jsx';

// eslint-disable-next-line
const app = new Framework7({
  name: 'F1-Weekly', // App name
  theme: 'auto', // Automatic theme detection
  el: '#app', // App root element
  component: App, // App main component
  cacheDuration: 0 /* set caching expire time to 0 */,
  store,
  routes,
  // Register service worker (only on production build)
  serviceWorker:
    process.env.NODE_ENV === 'production'
      ? {
        path: '/service-worker.js',
      }
      : {},
});
