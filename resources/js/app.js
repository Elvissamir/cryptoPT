require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import  Toast from 'vue-toastification'

//FONT AWESOME
import { library } from '@fortawesome/fontawesome-svg-core'
import { faEnvelope, faGlobe, faUser, faPlus, faHome, faCoins, faSuitcase, faCog, faSignOutAlt, faPencilAlt, faTimes, faAngleDown, faAngleUp } from '@fortawesome/free-solid-svg-icons'
import { faLinkedin, faGithub } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// Styles
import "vue-toastification/dist/index.css"

// ICONS
const solidIcons = [faEnvelope, faGlobe, faUser, faPlus, faHome, faCoins, faSuitcase, faCog, faSignOutAlt, faPencilAlt, faTimes, faAngleUp, faAngleDown];
const brandIcons = [faLinkedin, faGithub];

// ADD ICONS
solidIcons.forEach((icon) => library.add(icon));
brandIcons.forEach((icon) => library.add(icon));

// GET APP ELEMENT
const el = document.getElementById('app');

// CREATE APP COMPONENT
createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .component('font-awesome-icon', FontAwesomeIcon)
    .mixin({ methods: { route } })
    .use(InertiaPlugin)
    .use(Toast)
    .mount(el);

InertiaProgress.init({ color: '#4B5563' });
