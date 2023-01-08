window.reventzBus = new window.reventz.Vue();

window.reventz.Vue.mixin({
    methods: {
        applyFilters: window.reventz.applyFilters,
        addFilter: window.reventz.addFilter,
        addAction: window.reventz.addFilter,
        doAction: window.reventz.doAction,
        $get: window.reventz.$get,
        $adminGet: window.reventz.$adminGet,
        $adminPost: window.reventz.$adminPost,
        $post: window.reventz.$post,
        $publicAssets: window.reventz.$publicAssets
    }
});

import {routes} from './routes';

const router = new window.reventz.Router({
    routes: window.reventz.applyFilters('reventz_global_vue_routes', routes),
    linkActiveClass: 'active'
});

import App from './AdminApp';

new window.reventz.Vue({
    el: '#reventz_app',
    render: h => h(App),
    router: router
});

