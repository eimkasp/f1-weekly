import Discover from '../pages/discover.f7.jsx';
import Backlog from '../pages/backlog.f7.jsx';
import Wishlist from '../pages/wishlist.f7.jsx';
import Archive from '../pages/archive.f7.jsx';
import Game from '../pages/game.f7.jsx';
import Driver from '../pages/driver.f7.jsx';
import Search from '../pages/search.f7.jsx';

const routes = [
  {
    path: '/',
    component: Discover,
  },
  {
    path: '/backlog/',
    component: Backlog,
  },
  {
    path: '/wishlist/',
    component: Wishlist,
  },
  {
    path: '/archive/',
    component: Archive,
  },
  {
    path: '/game/:id/',
    popup: {
      component: Game,
      swipeToClose: 'to-bottom',
    },
  },
  {
    path: '/driver/:id/',
    popup: {
      component: Driver,
      swipeToClose: 'to-bottom',
    },
  },
  {
    path: '/search/',
    popup: {
      component: Search,
      swipeToClose: 'to-bottom',
    },
  },
];

export default routes;
