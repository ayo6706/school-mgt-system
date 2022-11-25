
require('./bootstrap');

window.Vue = require('vue');

import { library } from '@fortawesome/fontawesome-svg-core'
import { faCoffee } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faCoffee);

Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.config.productionTip = false;

import {store} from './store';

import LecturerHomeComponent from "./components/courseLecturerComponents/LecturerHomeComponent.vue";
import DownloadTemplateComponent from "./components/courseLecturerComponents/DownloadTemplateComponent.vue";
import DepartmentOfficerHomeComponent from "./components/departmentalOfficerComponents/DepartmentOfficerHomeComponent";
import CollegeOfficerHomeComponent from "./components/collegeOfficerComponents/CollegeOfficerHomeComponent";


import Toasted from 'vue-toasted';

// let AnAnotherExaExampleComponent = require('./components/AnAnotherExaExampleComponent.vue');


// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

//
// const routes = [
//     {path: '/example', Component: AnExampleComponent},
//     {path: '/another-example', Component: AnAnotherExaExampleComponent}
// ];
//
// const router = new VueRouter({
//     routes
// });

const app = new Vue({
    el: '#app',
    store,
    // router,
    created() {
        console.log("hello world");
        this.$store.dispatch('getCurrentSession');
        this.$store.dispatch('getDepartments');
        this.$store.dispatch('getRegisteredCourse');
    },
    components: {
        LecturerHomeComponent,
        DownloadTemplateComponent,
        DepartmentOfficerHomeComponent,
        CollegeOfficerHomeComponent
    }
});

Vue.use(Toasted, {
    duration: 9000,
    position: "top-center",
    action : {
        text : 'Cancel',
        onClick : (e, toastObject) => {
            toastObject.goAway(0);
        }
    }
});
