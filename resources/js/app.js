import { createApp } from 'vue';
import App from './components/App.vue'; // Main app wrapper
import router from './router'; // Import router

const app = createApp(App);
app.use(router);
app.mount('#app'); // Ensure this ID matches the ID in your HTML file
