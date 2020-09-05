"use strict"
import Vue from 'vue'
const Helper = {
    install(vue) {
        // vue.prototype.appName = 
        vue.prototype.getImage = function (location, name) {
            return process.env.VUE_APP_IMAGE_URL + '/' + location + '/' + name
        }
    }
}

Vue.use(Helper)