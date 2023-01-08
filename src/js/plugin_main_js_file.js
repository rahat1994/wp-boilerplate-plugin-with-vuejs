import Vue from './elements';
import Router from 'vue-router';
Vue.use(Router);

import { applyFilters, addFilter, addAction, doAction } from '@wordpress/hooks';

export default class reventz {
    constructor() {
        this.applyFilters = applyFilters;
        this.addFilter = addFilter;
        this.addAction = addAction;
        this.doAction = doAction;
        this.Vue = Vue;
        this.Router = Router;
    }

    $publicAssets(path){
        return (window.reventzAdmin.assets_url + path);
    }

    $get(options) {
        return window.jQuery.get(window.reventzAdmin.ajaxurl, options);
    }

    $adminGet(options) {
        options.action = 'reventz_admin_ajax';
        return window.jQuery.get(window.reventzAdmin.ajaxurl, options);
    }

    $post(options) {
        return window.jQuery.post(window.reventzAdmin.ajaxurl, options);
    }

    $adminPost(options) {
        options.action = 'reventz_admin_ajax';
        return window.jQuery.post(window.reventzAdmin.ajaxurl, options);
    }

    $getJSON(options) {
        return window.jQuery.getJSON(window.reventzAdmin.ajaxurl, options);
    }
}
