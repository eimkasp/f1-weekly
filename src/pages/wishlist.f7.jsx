import GameCards from '../components/GameCards.f7.jsx';

export default (props, { $store, $theme }) => {
  const { wishlist } = $store.getters;
  setTimeout(
    () => { window.dispatchEvent(new Event('resize')); },
    2000
  );
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
          <div class="title">Newsletter</div>
        </div>
      </div>
      <div class="page-content">
        <div class="page-title">Subscribe to <br /> newsletter</div>
        {wishlist.value.length > 0 ? (
          <GameCards small grid games={wishlist} />
        ) : (
          <div class="block collection-placeholder" style="margin-bottom: 40px;">
            Formula-1. In your inbox. Once a week.
            <div>
              <a
                class="col button button-large button-round button-fill link external"
                style="margin-top: 50px;"
                href="https://f1-weekly.us14.list-manage.com/subscribe?u=9ccdc903b7654272517b9c0ba&id=805a52ba06"
                target="_system">
                Subscribe
              </a>

            </div>
          </div>

        )}

        <div style="padding-left: 20px; padding-right: 20px;">
          <div class="elfsight-app-ffd38b82-35cd-440f-89be-ae460ddcb77c"></div>

        </div>
      </div>
    </div>
  );
};
