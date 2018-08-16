import axios from "axios/index";
import * as mutations from "./mutations";



export const CURRENT_USER__LOAD = 'loadCurrentUser';
export const CURRENT_USER__LOGOUT = 'logoutCurrentUser';


export default {

    [CURRENT_USER__LOAD] ({ commit }) {
        axios.get('/api/me').then(result => {
            commit(mutations.CURRENT_USER_SET, result.data.data);
        }).catch(error => {
            console.log(error);
        });
        commit(mutations.CURRENT_USER_SET);
    },
    [CURRENT_USER__LOGOUT] ({ commit }) {
        axios.post('/logout').then(result => {
            commit(mutations.CURRENT_USER_RESET);
            window.location.reload(true);
        }).catch(error => {
            console.log(error);
        });
    }

};