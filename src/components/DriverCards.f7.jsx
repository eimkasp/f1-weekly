import { gamePlatforms } from '../js/game-platforms.js';
import './GameCards.less';

export default function DriverCards(props) {
  const { games, metacritic, grid, small, skeleton } = props;

  const classes = () => [
    'block game-cards',
    small ? 'game-cards-small' : '',
    grid ? 'game-cards-grid' : 'scroll-block',
    skeleton ? 'skeleton-effect-wave' : '',
  ];

  return () => (
    <div class={classes().join(' ')}>
      
      {/* show skeleton loading when no items */}
      {skeleton &&
        Array.from({ length: 12 }).map((_, index) => (
          <a key={`loading-${index}`} class="game-card scroll-block-item">
            <div class="game-card-image" />
            <div class="game-card-logos skeleton-text">Logos placeholder</div>
            <div class="game-card-name skeleton-text">Name placeholder</div>
          </a>
        ))}
      {/* otherwise show items */}
      {!skeleton &&
        (games.value || games ).map((game) => (
          
          <a
            key={game.id}
            class="game-card scroll-block-item"
            href="#"
          >
            <div class="game-card-image">
              <img src={`${game.driver.image}`} class="loaded" style="background: white; object-fit: contain;" />
            </div>

            <div class="game-card-logos">
            <div>
              <span class='badge color-deeporange'>{  game.points } Points</span>
            </div>

            </div>
            <div class="game-card-name">{game.driver.name}</div>
          </a>
        ))}
    </div>
  );
}
