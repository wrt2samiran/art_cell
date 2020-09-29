require('./bootstrap');
window.Vue = require('vue');


var vm = new Vue({
                el: '#app',
                i18n,
                router,
                vuetify,
            components:{
                'App':App
            }
        });

global.vm = vm;