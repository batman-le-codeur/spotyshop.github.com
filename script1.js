// Simple carousel centering the "active" card
const carousel = document.getElementById('carousel');
const track = document.getElementById('track');
const cards = Array.from(track.querySelectorAll('.card'));
const prevBtn = document.getElementById('prev');
const nextBtn = document.getElementById('next');

let current = cards.findIndex(c => c.classList.contains('active'));
if (current === -1) current = Math.floor(cards.length / 2);

function clamp(v, a, b) { return Math.max(a, Math.min(b, v)); }

function update() {
  const activeCard = cards[current];
  // remove / set active classes
  cards.forEach(c => c.classList.remove('active'));
  activeCard.classList.add('active');

  // center active card in the viewport
  const viewport = carousel.querySelector('.viewport');
  const viewportWidth = viewport.clientWidth;
  const cardLeft = activeCard.offsetLeft;
  const cardWidth = activeCard.clientWidth;
  const offset = cardLeft - (viewportWidth - cardWidth) / 2;
  track.style.transform = `translateX(${-offset}px)`;

  // disable arrows visually if at ends
  prevBtn.disabled = current === 0;
  nextBtn.disabled = current === cards.length - 1;
  prevBtn.style.opacity = prevBtn.disabled ? 0.45 : 1;
  nextBtn.style.opacity = nextBtn.disabled ? 0.45 : 1;
}

prevBtn.addEventListener('click', () => {
  current = clamp(current - 1, 0, cards.length - 1);
  update();
});
nextBtn.addEventListener('click', () => {
  current = clamp(current + 1, 0, cards.length - 1);
  update();
});

// keyboard navigation
window.addEventListener('keydown', (e) => {
  if (e.key === 'ArrowLeft') prevBtn.click();
  if (e.key === 'ArrowRight') nextBtn.click();
});

// click on a card -> make it active
cards.forEach((card, idx) => {
  card.addEventListener('click', () => {
    current = idx;
    update();
  });
});

// handle resize -> recenter
window.addEventListener('resize', () => {
  // small timeout to let layout settle
  setTimeout(update, 60);
});

// initial position
update();
