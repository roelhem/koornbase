import Vue from "vue";
import Vuex from "vuex";
import {CURRENT_USER_RESET, CURRENT_USER_SET} from "./constants/mutation-types";
import axios from "axios";

Vue.use(Vuex);




export const store = new Vuex.Store({

    state: {
        currentUser: null
    },

    computed: {
        currentUserLoaded() {
            return currentUser !== null;
        }
    },

    mutations: {
        [CURRENT_USER_SET] (state, payload) {
            state.currentUser = payload;
        },
        [CURRENT_USER_RESET] (state) {
            state.currentUser = null;
        }
    },

    actions: {
        loadCurrentUser ({ commit }) {
            axios.get('/api/me', {
                params: {
                    fields:['avatar','name_display','person_id']
                }
            }).then(result => {
                commit(CURRENT_USER_SET, result.data.data);
            }).catch(error => {
                console.log(error);
            });
            commit(CURRENT_USER_SET);
        },
        logoutCurrentUser ({ commit }) {
            axios.post('/logout').then(result => {
                commit(CURRENT_USER_RESET);
                window.location.reload(true);
            }).catch(error => {
                console.log(error);
            });
        }
    }

});

export default store;