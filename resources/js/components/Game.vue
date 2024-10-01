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
  
  <script>
  export default {
    data() {
      return {
        canvas: null,
        ctx: null,
        snake: [{ x: 10, y: 10 }],
        direction: { x: 1, y: 0 },
        food: { x: 15, y: 15 },
        gridSize: 20,
        tileSize: 20,
        score: 0,
        gameInterval: null,
        gameOver: false,
      };
    },
    mounted() {
      this.initializeGame();
      window.addEventListener("keydown", this.changeDirection);
    },
    beforeDestroy() {
      clearInterval(this.gameInterval);
      window.removeEventListener("keydown", this.changeDirection);
    },
    methods: {
      initializeGame() {
        this.canvas = this.$refs.gameCanvas;
        this.ctx = this.canvas.getContext("2d");
        this.canvas.width = this.gridSize * this.tileSize;
        this.canvas.height = this.gridSize * this.tileSize;
  
        this.gameInterval = setInterval(this.updateGame, 100);
      },
      changeDirection(e) {
        switch (e.keyCode) {
          case 37: // Left arrow
            if (this.direction.x !== 1) {
              this.direction = { x: -1, y: 0 };
            }
            break;
          case 38: // Up arrow
            if (this.direction.y !== 1) {
              this.direction = { x: 0, y: -1 };
            }
            break;
          case 39: // Right arrow
            if (this.direction.x !== -1) {
              this.direction = { x: 1, y: 0 };
            }
            break;
          case 40: // Down arrow
            if (this.direction.y !== -1) {
              this.direction = { x: 0, y: 1 };
            }
            break;
        }
      },
      updateGame() {
        this.moveSnake();
        this.checkCollision();
        this.drawGame();
      },
      moveSnake() {
        const newHead = {
          x: this.snake[0].x + this.direction.x,
          y: this.snake[0].y + this.direction.y,
        };
  
        this.snake.unshift(newHead);
  
        if (newHead.x === this.food.x && newHead.y === this.food.y) {
          this.score += 1;
          this.playEatSound();
          this.placeFood();
        } else {
          this.snake.pop();
        }
      },
      checkCollision() {
        const head = this.snake[0];
  
        if (
          head.x < 0 ||
          head.x >= this.gridSize ||
          head.y < 0 ||
          head.y >= this.gridSize
        ) {
          this.endGame();
        }
  
        for (let i = 1; i < this.snake.length; i++) {
          if (this.snake[i].x === head.x && this.snake[i].y === head.y) {
            this.endGame();
          }
        }
      },
      endGame() {
        this.gameOver = true;
        clearInterval(this.gameInterval);
        this.playCollisionSound();
      },
      resetGame() {
        this.snake = [{ x: 10, y: 10 }];
        this.direction = { x: 1, y: 0 };
        this.score = 0;
        this.gameOver = false;
        this.placeFood();
        this.gameInterval = setInterval(this.updateGame, 100);
      },
      placeFood() {
        this.food = {
          x: Math.floor(Math.random() * this.gridSize),
          y: Math.floor(Math.random() * this.gridSize),
        };
      },
      drawGame() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
  
        // Estilo de la serpiente
        this.ctx.fillStyle = "#32CD32";
        this.ctx.shadowBlur = 10;
        this.ctx.shadowColor = "rgba(0, 0, 0, 0.5)";
        this.snake.forEach((segment) => {
          this.ctx.fillRect(
            segment.x * this.tileSize,
            segment.y * this.tileSize,
            this.tileSize,
            this.tileSize
          );
        });
        this.ctx.shadowBlur = 0;
  
        // Estilo de la comida
        this.ctx.fillStyle = "#FF6347";
        this.ctx.beginPath();
        this.ctx.arc(
          this.food.x * this.tileSize + this.tileSize / 2,
          this.food.y * this.tileSize + this.tileSize / 2,
          this.tileSize / 2,
          0,
          2 * Math.PI
        );
        this.ctx.fill();
      },
      playEatSound() {
        const eatSound = new Audio("eat.mp3");
        eatSound.play();
      },
      playCollisionSound() {
        const collisionSound = new Audio("collision.mp3");
        collisionSound.play();
      },
    },
  };
  </script>
  
  <style>
  @import url("https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap");
  
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