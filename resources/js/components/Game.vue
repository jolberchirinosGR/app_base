<template>
  <div class="game-container">
    <canvas ref="gameCanvas"></canvas>
    <p v-if="!gameOver">Puntuación: {{ score }}</p>
    <div v-if="gameOver" class="game-over">
      <p>Game Over</p>
      <button @click="resetGame" class="reset-btn">Try Again</button>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, watchEffect } from "vue";

// ⚡️ Variables reactivas
const gameCanvas = ref(null);
const ctx = ref(null);
const gameOver = ref(false);
const score = ref(0);
const gameInterval = ref(null);
const gridSize = 20;
const tileSize = 20;

// ⚡️ Estado del juego
const snake = reactive([{ x: 10, y: 10 }]);
const direction = reactive({ x: 1, y: 0 });
const food = reactive({ x: 15, y: 15 });

// 🎮 Inicializar el juego
const initializeGame = () => {
  const canvas = gameCanvas.value;
  ctx.value = canvas.getContext("2d");
  canvas.width = gridSize * tileSize;
  canvas.height = gridSize * tileSize;

  placeFood();
  gameInterval.value = setInterval(updateGame, 100);
};

// 🔄 Actualizar el juego en cada frame
const updateGame = () => {
  moveSnake();
  checkCollision();
  drawGame();
};

// 🏃‍♂️ Mover la serpiente
const moveSnake = () => {
  const newHead = {
    x: snake[0].x + direction.x,
    y: snake[0].y + direction.y,
  };

  snake.unshift(newHead);

  if (newHead.x === food.x && newHead.y === food.y) {
    score.value += 1;
    placeFood();
  } else {
    snake.pop();
  }
};

// 🚧 Verificar colisiones
const checkCollision = () => {
  const head = snake[0];

  // Colisión con los bordes
  if (head.x < 0 || head.x >= gridSize || head.y < 0 || head.y >= gridSize) {
    endGame();
  }

  // Colisión con el cuerpo
  for (let i = 1; i < snake.length; i++) {
    if (snake[i].x === head.x && snake[i].y === head.y) {
      endGame();
    }
  }
};

// ❌ Finalizar el juego
const endGame = () => {
  gameOver.value = true;
  clearInterval(gameInterval.value);
};

// 🔄 Reiniciar el juego
const resetGame = () => {
  snake.splice(0, snake.length, { x: 10, y: 10 });
  direction.x = 1;
  direction.y = 0;
  score.value = 0;
  gameOver.value = false;
  placeFood();
  gameInterval.value = setInterval(updateGame, 100);
};

// 🍎 Colocar comida en una nueva posición
const placeFood = () => {
  food.x = Math.floor(Math.random() * gridSize);
  food.y = Math.floor(Math.random() * gridSize);
};

// 🎨 Dibujar el juego en el canvas
const drawGame = () => {
  const canvas = gameCanvas.value;
  const context = ctx.value;

  context.clearRect(0, 0, canvas.width, canvas.height);

  // Dibujar serpiente
  context.fillStyle = "#32CD32";
  context.shadowBlur = 10;
  context.shadowColor = "rgba(0, 0, 0, 0.5)";
  snake.forEach((segment) => {
    context.fillRect(
      segment.x * tileSize,
      segment.y * tileSize,
      tileSize,
      tileSize
    );
  });
  context.shadowBlur = 0;

  // Dibujar comida
  context.fillStyle = "#FF6347";
  context.beginPath();
  context.arc(
    food.x * tileSize + tileSize / 2,
    food.y * tileSize + tileSize / 2,
    tileSize / 2,
    0,
    2 * Math.PI
  );
  context.fill();
};

// 🎮 Cambiar dirección con el teclado
const changeDirection = (e) => {
  switch (e.keyCode) {
    case 37: // Izquierda
      if (direction.x !== 1) {
        direction.x = -1;
        direction.y = 0;
      }
      break;
    case 38: // Arriba
      if (direction.y !== 1) {
        direction.x = 0;
        direction.y = -1;
      }
      break;
    case 39: // Derecha
      if (direction.x !== -1) {
        direction.x = 1;
        direction.y = 0;
      }
      break;
    case 40: // Abajo
      if (direction.y !== -1) {
        direction.x = 0;
        direction.y = 1;
      }
      break;
  }
};

const preventScrollKeys = (e) => {
  const keys = [37, 38, 39, 40]; // Flechas de dirección
  if (keys.includes(e.keyCode)) {
    e.preventDefault();
  }
};

onMounted(() => {
  window.addEventListener("keydown", preventScrollKeys, { passive: false });
});

onUnmounted(() => {
  window.removeEventListener("keydown", preventScrollKeys);
});

// 🚀 Iniciar el juego al montar el componente
onMounted(() => {
  initializeGame();
  window.addEventListener("keydown", changeDirection);
});

// 🛑 Limpiar intervalos y eventos al desmontar
onUnmounted(() => {
  clearInterval(gameInterval.value);
  window.removeEventListener("keydown", changeDirection);
});
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap");
/* 
html, body {
  margin: 0;
  padding: 0;
  overflow: hidden; 
  width: 100%;
  height: 100%;
} */

.game-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: #f0f0f0;
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
  margin-top: 50px;
}

canvas {
  border: 2px solid #333;
  border-radius: 10px;
  background: linear-gradient(135deg, #87ceeb, #4682b4);
  transition: all 0.1s ease-in-out;
}

.game-over p {
  font-family: "Press Start 2P", cursive;
  font-size: 24px;
  color: #ff6347;
  margin: 20px 0;
}

.reset-btn {
  background-color: #ff6347;
  color: #fff;
  border: none;
  padding: 10px 20px;
  margin-top: 15px;
  font-size: 16px;
  font-family: "Press Start 2P", cursive;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s;
}

.reset-btn:hover {
  background-color: #ff4500;
}
</style>
