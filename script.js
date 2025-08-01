function mensagem() {
  alert("Você clicou no botão!");
}

const slider = document.getElementById('slider');
const next = document.getElementById('next');
const prev = document.getElementById('prev');
const slides = document.querySelectorAll('.slide');
const indicadores = document.getElementById('indicadores');

let index = 0;
const totalSlides = slides.length;

// Criar indicadores (bolinhas)
slides.forEach((_, i) => {
  const dot = document.createElement('div');
  dot.addEventListener('click', () => {
    index = i;
    updateCarousel();
  });
  indicadores.appendChild(dot);
});

function updateCarousel() {
  slider.style.transform = `translateX(${-index * 100}%)`;
  document.querySelectorAll('.indicadores div').forEach((dot, i) => {
    dot.classList.toggle('ativo', i === index);
  });
}

next.addEventListener('click', () => {
  index = (index + 1) % totalSlides;
  updateCarousel();
});

prev.addEventListener('click', () => {
  index = (index - 1 + totalSlides) % totalSlides;
  updateCarousel();
});

// Auto play
setInterval(() => {
  index = (index + 1) % totalSlides;
  updateCarousel();
}, 5000);

// Inicializa
updateCarousel();

// Suporte para touch (arrastar no celular)
let startX = 0;
slider.addEventListener('touchstart', (e) => {
  startX = e.touches[0].clientX;
});

slider.addEventListener('touchend', (e) => {
  let endX = e.changedTouches[0].clientX;
  if (startX - endX > 50) {
    index = (index + 1) % totalSlides;
    updateCarousel();
  } else if (endX - startX > 50) {
    index = (index - 1 + totalSlides) % totalSlides;
    updateCarousel();
  }
});