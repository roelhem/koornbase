import Vue from "vue";
import Vuex from "vuex";
import mutations from './mutations';
import actions from './actions';

Vue.use(Vuex);


export const store = new Vuex.Store({

    state: {
        currentUser: null,
        pageTitle: 'Koornbase Dashboard',
    },

    computed: {
        currentUserLoaded() {
            return currentUser !== null;
        }
    },

    mutations,
    actions,

    modules: {}



});

export default store;