import GameCards from '../components/GameCards.f7.jsx';

export default (props, { $store, $theme }) => {
  const { archive } = $store.getters;
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
          <div class="title">About F1-Weekly</div>
        </div>
      </div>
      <div class="page-content">
        <div class="page-title">About F1-Weekly</div>
        {archive.value.length > 0 ? (
          <GameCards small grid games={archive} />
        ) : (
          <div class="block collection-placeholder">
             <a
          class="col button button-large button-round button-fill"
          style="margin-top: 50px;"
          href="https://github.com/eimkasp/f1-weekly" 
          target="_system">
            Visit our github
            </a>

            <a
          class="col button button-large button-round button-fill"
          style="margin-top: 50px;"
          href="https://www.instagram.com/f1weekly.live/" 
          target="_system">
            Visit our Instagram @f1weekly.live
            </a>

          </div>
        )}
      </div>
    </div>
  );
};
