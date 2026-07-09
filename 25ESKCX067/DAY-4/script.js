let count1 = 0;
let count2 = 0;
let timeLeft = 30;
let gameActive = true;


const img1 = document.getElementById("img1");
const img2 = document.getElementById("img2");
const countDisplay1 = document.getElementById("count1");
const countDisplay2 = document.getElementById("count2");
const timerDisplay = document.getElementById("timer");
const gameOverDisplay = document.getElementById("game-over");


img1.addEventListener("click", function() {
  if (gameActive) {
    count1++;
    countDisplay1.textContent = count1;
  }
});

img2.addEventListener("click", function() {
  if (gameActive) {
    count2++;
    countDisplay2.textContent = count2;
  }
});


let countdown = setInterval(() => {
  if (timeLeft > 0) {
    timeLeft--;
    timerDisplay.textContent = `⏳ Time Left: ${timeLeft}s`;
  } else {
    clearInterval(countdown);
    gameActive = false;
    timerDisplay.textContent = "⏳ Time Left: 0s";
    gameOverDisplay.textContent = "🎉 Game Over! Thanks for playing!";
  }
}, 1000);
