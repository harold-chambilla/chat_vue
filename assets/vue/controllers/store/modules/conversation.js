import axios from 'axios';
// import { createStore } from 'vuex';

export default {
    namespaced: true,
    state: {
        conversations: [],
    },
    getters: {
        CONVERSATIONS: state => { return state.conversations; },
    },
    mutations: {
        SET_CONVERSATIONS: (state, payload) => {
            state.conversations = payload;
        }
    },
    actions: {
        GET_CONVERSATIONS: ({commit}) => {
            axios.get('/conversation')
                .then(response => {
                    commit("SET_CONVERSATIONS", response.data.conversations)
                })
        }
    }
}
