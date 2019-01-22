
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

Vue.prototype.signedIn = window.App.signedIn;

window.events = new Vue();

window.flash = function( message, level = 'success' )
{
    window.events.$emit( 'flash', { message, level } );
};

Vue.config.ignoredElements = ['trix-editor'];

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/Flash.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('paginator', require('./components/Paginator.vue').default);
Vue.component('user-notifications', require('./components/UserNotifications.vue').default);
Vue.component('avatar-form', require('./components/AvatarForm.vue').default);

Vue.component('activities', require('./components/Activities.vue').default);
Vue.component('activity-layout', require('./components/ActivityLayout.vue').default);
Vue.component('activity-favorite', require('./components/ActivityFavorite.vue').default);
Vue.component('activity-reply', require('./components/ActivityReply.vue').default);
Vue.component('activity-thread', require('./components/ActivityThread.vue').default);
Vue.component('wysiwyg', require('./components/Wysiwyg.vue').default);
Vue.component('dropdown', require('./components/Dropdown.vue').default);
Vue.component('channel-dropdown', require('./components/ChannelDropdown.vue').default);
Vue.component('logout-button', require('./components/LogoutButton.vue').default);
Vue.component('login', require('./components/Login.vue').default);
Vue.component('register', require('./components/Register.vue').default);
Vue.component("highlight", require("./components/Highlight").default);

Vue.component('thread-view', require('./pages/Thread.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data:
    {
        searching: false,
    },
    methods:
    {
        search()
        {
            this.searching = true;

            this.$nextTick( () =>
            {
                this.$refs.search.focus();
            } );
        }
    }
});
