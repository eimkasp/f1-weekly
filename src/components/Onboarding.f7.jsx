import './Onboarding.less';

export default function Onboarding(
  props,
  { $f7, $el, $onMounted, $onBeforeUnmount },
) {
  const enabled = !localStorage.onboardingFinished;
  let swiper;
  let popup;

  const createSwiper = () => {
    swiper = $f7.swiper.create({
      el: '.onboarding .swiper-container',
      observer: true,
      observeParents: true,
      effect: 'coverflow',
      speed: 600,
      coverflowEffect: {
        rotate: 0,
        depth: 400,
        stretch: 0,
      },
      pagination: {
        el: '.onboarding .swiper-pagination',
      },
    });
  };
  const createPopup = () => {
    popup = $f7.popup.create({
      el: $el.value,
      opened: true,
    });
    popup.open(false);
  };
  const destroySwiper = () => {
    if (swiper) swiper.destroy();
  };
  const destroyPopup = () => {
    if (popup) popup.destroy();
  };
  $onMounted(() => {
    if (!enabled) return;
    createPopup();
    createSwiper();
  });
  $onBeforeUnmount(() => {
    destroyPopup();
    destroySwiper();
  });

  const slideNext = () => {
    swiper.slideNext();
  };

  const closeOnboarding = () => {
    $f7.popup.close('.popup-onboarding', true);
    localStorage.onboardingFinished = true;
  };

  // Return empty div when onboarding is disabled
  return () =>
    !enabled ? (
      <div />
    ) : (
      <div
        class="popup popup-onboarding onboarding popup-tablet-fullscreen modal-in"
        onPopupClosed={destroySwiper}
      >
        <div class="swiper-container">
          <div class="swiper-pagination" />
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <div class="onboarding-content">
                <div class="onboarding-icon">
                  <img src="icons/128x128.png" />
                </div>
                <div class="onboarding-title">F1-Weekly</div>
                <div class="onboarding-text">
                  Formula-1. In your inbox. Once a week.
                </div>
                <div class="onboarding-next">
                  <a href="#" class="button button-small" onClick={slideNext}>
                    Next
                  </a>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="onboarding-content">
                <div class="onboarding-icon">
                  <i class="icon f7-icons ios-only">compass_fill</i>
                  <i class="icon material-icons md-only">explore</i>
                </div>
                <div class="onboarding-title">Discover</div>
                <div class="onboarding-text">
                  Search and discover thousands of video games, upcoming and
                  recent releases.
                </div>
                <div class="onboarding-next">
                  <a href="#" class="button button-small" onClick={slideNext}>
                    Next
                  </a>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="onboarding-content">
                <div class="onboarding-icon">
                  <i class="icon f7-icons ios-only">gamecontroller_fill</i>
                  <i class="icon material-icons md-only">sports_esports</i>
                </div>
                <div class="onboarding-title">Standings</div>
                <div class="onboarding-text">
                  See the current standings of the Formula-1 world.
                </div>
                <div class="onboarding-next">
                  <a href="#" class="button button-small" onClick={slideNext}>
                    Next
                  </a>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="onboarding-content">
                <div class="onboarding-icon">
                  <i class="icon f7-icons ios-only">
                    square_favorites_alt_fill
                  </i>
                  <i class="icon material-icons md-only">
                    collections_bookmark
                  </i>
                </div>
                <div class="onboarding-title">Newsletter</div>
                <div class="onboarding-text">
                  Sign up to our newsletter to get all the latest news and important information about Formula 1 season weekly.
                </div>
                <div class="onboarding-next">
                  <a href="#" class="button button-small" onClick={slideNext}>
                    Next
                  </a>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="onboarding-content">
                <div class="onboarding-icon">
                  <i class="icon f7-icons ios-only">tray_fill</i>
                  <i class="icon material-icons md-only">inventory_2</i>
                </div>
                <div class="onboarding-title">About us</div>
                <div class="onboarding-text">
                  We are a team of Formula 1 enthusiasts and we are building this open source project to share the news in 
                  easy and accessible way for everyone.
                  
                </div>
                <div class="onboarding-next">
                  <a href="#" class="button button-small" onClick={slideNext}>
                    Next
                  </a>
                </div>
              </div>
            </div>
            <div class="swiper-slide">
              <div class="onboarding-content">
                <div class="onboarding-icon">????</div>
                <div class="onboarding-title">Let's Play</div>
                <div class="onboarding-text">
                  Start from searching your favorite games or browse upcoming
                  and recent releases.
                </div>
                <div class="onboarding-next">
                  <a
                    href="#"
                    class="button button-small"
                    onClick={closeOnboarding}
                  >
                    Show me games!
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
}
