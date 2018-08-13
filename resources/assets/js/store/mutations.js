

export const CURRENT_USER_SET = 'CURRENT_USER_SET';
export const CURRENT_USER_RESET = 'CURRENT_USER_RESET';

export const PAGE_TITLE_SET = 'PAGE_TITLE_SET';



export default {

    [CURRENT_USER_SET] (state, payload) {
        state.currentUser = payload;
    },
    [CURRENT_USER_RESET] (state) {
        state.currentUser = null;
    },


    [PAGE_TITLE_SET] (state, title) {
        state.pageTitle = title;
    }

}