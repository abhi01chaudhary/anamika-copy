import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const state = {
    user: null,
    isLoggedIn: false,
}

const getters = {
    user : (state) => {
        return state.user
    },
    isLoggedIn : (state) => {
        return state.isLoggedIn
    }
}

const actions = {
    user(context, user){
        context.commit('user', user)
        context.commit('isLoggedIn', true)
    }
}

const mutations = {
    user(state, user){
        state.user = user
    },
    isLoggedIn(state, isLoggedIn){
        state.isLoggedIn = isLoggedIn
    }
}

export default new Vuex.Store({
    state,
    getters,
    mutations,
    actions
})