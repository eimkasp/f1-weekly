import DriverCards from '../components/DriverCards.f7.jsx';
import GameCards from '../components/GameCards.f7.jsx';

export default (props, { $store, $theme }) => {
  const { topGames } = $store.getters;
  console.log(topGames);
  const icon = $theme.ios ? (
    <i class="icon f7-icons">compass_fill</i>
  ) : (
    <i class="icon material-icons">explore</i>
  );
  return () => (
    <div class="page">
      <div class="navbar navbar-transparent">
        <div class="navbar-bg" />
        <div class="navbar-inner">
          <div class="title">Backlog</div>
        </div>
      </div>
      <div class="page-content">
        <div class="page-title">Backlog</div>
        {topGames.value.length > 0 ? (
          <DriverCards small grid games={topGames} />
        ) : (
          <div class="block collection-placeholder">
           Standings are beeing updated. Check back shortly
          </div>
        )}
      </div>
    </div>
  );
};
