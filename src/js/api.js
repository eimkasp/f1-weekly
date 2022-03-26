const API_KEY = '56d6e875c750dd7cb5426da584b18353';
const endpoint = 'https://v1.formula-1.api-sports.io';
const headers = {
  method: 'get',
  headers: new Headers({
    'x-rapidapi-key': '56d6e875c750dd7cb5426da584b18353',
    'x-rapidapi-host': 'v1.formula-1.api-sports.io'
  }),
};

const api = {
  getTopGames() {
    return fetch(`${endpoint}/races/?season=2022&type=race`,
      headers
    ).then((res) => res.json());
  },
  getUpcomingRaces() {
    return fetch(
      `${endpoint}/races/?season=2022&type=race`, headers).then((res) => res.json());
  },
  getRecentRaces() {
    return fetch(
      `${endpoint}/races/?season=2022&type=race`, headers).then((res) => res.json());
  },
  getRace(id) {
    return Promise.all([
      // fetch screenshots
      fetch(`${endpoint} /competitions/?id=${id}`, headers).then((res) =>
        res.json(),
      ),
      // fetch game
      fetch(`${endpoint} /races/?id=${id}`, headers).then((res) => res.json()),
    ]).then(([game]) => {
      return {
        ...game,
      };
    });
  },
  search(query) {
    return fetch(
      `${endpoint}/races`, headers
    ).then((res) => res.json());
  },
  searchNext(url) {
    return fetch(url, headers).then((res) => res.json());
  },
};

window.api = api;

export default api;
