import { defineStore } from 'pinia';
import { initialState } from './initial_state';
import { getters }      from './getters';
import { actions }      from './actions';

export const useHomePageStore = defineStore('home_page', {
    state: () => ({ ...initialState }),
    getters: { ...getters },
    actions: { ...actions },
});
