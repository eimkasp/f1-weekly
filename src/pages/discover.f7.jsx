import DriverCards from '../components/DriverCards.f7.jsx';
import GameCards from '../components/GameCards.f7.jsx';

export default (props, { $store, $onMounted }) => {
  const { recentRaces, upcomingGames, topGames } = $store.getters;

  const fetchData = () => {
    $store.dispatch('getUpcomingRaces');
    $store.dispatch('getRecentRaces');
    $store.dispatch('getTopGames');
  };

  

  $onMounted(() => {
    fetchData();
  });

  return () => (
    <div class="page">
      <div class="navbar navbar-transparent">
        <div class="navbar-bg" />
        <div class="navbar-inner">
          <div class="title">F1-Weekly</div>
          <div class="right">
            <a href="/search/" class="link icon-only">
              <i class="icon f7-icons ios-only">search</i>
              <i class="icon material-icons md-only">search</i>
            </a>
          </div>
        </div>
      </div>
      <div class="page-content">
        {/* <img src="https://scontent.fkun1-1.fna.fbcdn.net/v/t39.30808-6/277368253_106771571981473_844964204046739531_n.jpg?_nc_cat=103&ccb=1-5&_nc_sid=09cbfe&_nc_ohc=I-pOc-242PcAX_8dt67&tn=24HC3XIzjvAYxgqU&_nc_ht=scontent.fkun1-1.fna&oh=00_AT-WVssY4Q5HDsl2IUg1kflRFHvGmZsB6EQYDI_1iXt7ZQ&oe=6243D313"  /> */}
        <div class="page-title">👋 Welcome to <br /> F1 Weekly</div>
        <div class="block-title block-title-medium">Upcoming Races</div>
        {upcomingGames.value.length > 0 ? (
          <GameCards key="upcoming" games={upcomingGames} />
        ) : (
          <GameCards key="upcoming-skeleton" skeleton />
        )}

        <div class="block-title block-title-medium">Recent Races</div>
        {recentRaces.value.length > 0 ? (
          <GameCards key="recent" games={recentRaces} />
        ) : (
          <GameCards key="recent-skeleton" skeleton />
        )}

        <div class="block-title block-title-medium">Top Drivers / Standings</div>
        {topGames.value.length > 0 ? (
          <DriverCards key="top" games={topGames} metacritic />
        ) : (
          <DriverCards key="top-skeleton" skeleton />
        )}
      </div>
    </div>
  );
};
