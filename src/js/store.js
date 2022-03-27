import { createStore } from 'framework7';
import api from './api.js';
import { races } from './data.js';
import { races as all_races } from './data/races.js';
import { standings_drivers as drivers } from './data/standings-drivers.js';


const getFromLocalStorage = (key, defaultValue) => {
  return localStorage[key] ? JSON.parse(localStorage[key]) : defaultValue;
};

const store = createStore({
  state: {
    searchQuery: '',
    searchState: 'idle',
    searchResults: [],
    searchRecent: getFromLocalStorage('searchRecent', []),
    searchNext: true,
    searchNextLoading: false,
    backlog: getFromLocalStorage('backlog', []),
    archive: getFromLocalStorage('archive', []),
    wishlist: getFromLocalStorage('wishlist', []),
    topGames: getFromLocalStorage('races', drivers).sort((a, b) => (a.position < b.position)),
    recentRaces: getFromLocalStorage('races', all_races).filter((item) => (item.status == 'Completed' && item.type == "Race")),
    upcomingGames: getFromLocalStorage('races', all_races).filter((item) => (item.status != 'Completed' && item.status && item.type == "Race")),
  },
  getters: {
    searchResults: ({ state }) => state.searchResults,
    searchState: ({ state }) => state.searchState,
    searchRecent: ({ state }) => state.searchRecent,
    searchNext: ({ state }) => state.searchNext,
    searchNextLoading: ({ state }) => state.searchNextLoading,
    backlog: ({ state }) => state.backlog,
    archive: ({ state }) => state.archive,
    wishlist: ({ state }) => state.wishlist,
    topGames: ({ state }) => state.topGames,
    recentRaces: ({ state }) => state.recentRaces,
    upcomingGames: ({ state }) => state.upcomingGames,
  },
  actions: {
    getGameInLists({ state }, gameId) {
      return {
        inBacklog:
          state.backlog.filter((game) => game.id === gameId).length > 0,
        inArchive:
          state.archive.filter((game) => game.id === gameId).length > 0,
        inWishlist:
          state.wishlist.filter((game) => game.id === gameId).length > 0,
      };
    },
    async getTopGames({ state }) {
      // const response = await api.getTopGames();
      // console.log(response.response);
      return state.topGames;
    },
    async getRecentRaces({ state }) {
      // const response = await api.getRecentRaces();
      // console.log(response);
      console.log(state.recentGames);
      return state.recentGames;
    },
    async getUpcomingRaces({ state }) {
      // const response = await api.getUpcomingRaces();
      return state.upcomingGames;
    },
    addGameToList({ state }, { listName, game }) {
      const list = state[listName];
      const inList = list.filter((el) => el.id === game.id).length > 0;
      if (inList) return;
      list.push(game);
      state[listName] = [...list];
      localStorage[listName] = JSON.stringify(state[listName]);
    },
    removeGameFromList({ state }, { listName, game }) {
      const list = state[listName];
      const itemInList = list.filter((el) => el.id === game.id)[0];
      if (!itemInList) return;
      list.splice(list.indexOf(itemInList), 1);
      state[listName] = [...list];
      localStorage[listName] = JSON.stringify(state[listName]);
    },

    getRace(ctx, id) {
      return api.getGame(id);
    },

    async search({ state }, query) {
      if (query === state.searchQuery) {
        return;
      }
      // Save recent search
      if (!state.searchRecent.includes(query)) {
        state.searchRecent.unshift(query);
        state.searchRecent = [...state.searchRecent.slice(0, 12)];
        localStorage.searchRecent = JSON.stringify(state.searchRecent);
      }

      state.searchQuery = query;
      state.searchState = 'loading';
      state.searchResults = [];
      state.searchNext = true;

      const { results, next } = await api.search(query);

      if (!results || results.length === 0) {
        state.searchState = 'empty';
        state.searchResults = [];
        state.searchNext = null;
        return;
      }
      state.searchState = 'results';
      state.searchResults = [...results];
      state.searchNext = next;
    },

    async searchNext({ state }) {
      if (state.searchNextLoading || !state.searchNext) return;
      state.searchNextLoading = true;
      const { results, next } = await api.searchNext(state.searchNext);
      if (!results || results.length === 0) {
        state.searchNext = null;
        state.searchNextLoading = false;
        return;
      }
      state.searchResults = [...state.searchResults, ...results];
      state.searchNext = next;
      setTimeout(() => {
        state.searchNextLoading = false;
      });
    },
  },
});

export default store;
