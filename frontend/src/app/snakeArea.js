// Third-party modules
global.p2 = window.p2 = require('phaser/build/p2');
global.PIXI = window.PIXI = require('phaser/build/pixi');
const Phaser = global.Phaser = require('phaser/build/phaser');

// App modules
const CrossbarConnection = require('./crossbarConnection');
const networkDataTranslator = require('./network-data-translator');

const PLAYER_NAME_COLOR = '#FFFF00';//this is a text color: must be a hex string
const HEAD_COLOR = 0xFF0000;
const BODY_COLOR = 0xFFFFFF;

class SnakeArea {

  constructor(nodeId) {
    this.worldData = {players: [], map: {foods: []}};
    this.nodeId = nodeId;
  }

  init() {
    this.game = new Phaser.Game(window.innerWidth, window.innerHeight, Phaser.CANVAS, this.nodeId, {
      preload: this.preload.bind(this),
      create: this.create.bind(this),
      update: this.update.bind(this),
      render: this.render.bind(this)
    });

    CrossbarConnection.onopen = (session) => {
      session.publish('join', [], {playerName: window.playerName});

      session.subscribe('tick', (_, data) => {
        this.worldData = networkDataTranslator.makeWorldDataFromNetworkData(data);
      })
    };
  }

  preload() {
    this.game.stage.backgroundColor = '#000';
    this.game.load.image('bg', 'frontend/public/assets/bg.jpg');
  }

  render() {
    this.game.debug.cameraInfo(this.game.camera, 32, 32);
  }

  create() {
    this.game.world.resize(100000, 100000);

    this.game.camera.y = this.game.world.centerY - (this.game.camera.height / 2);
    this.game.camera.x = this.game.world.centerX - (this.game.camera.width / 2);

    this.bg = this.game.add.tileSprite(
      this.game.camera.x,
      this.game.camera.y,
      this.game.camera.width,
      this.game.camera.height,
      'bg'
    );
  }

  getCurrentUser() {
    for (const player of this.worldData.players) {
      if (player.name === window.playerName) {
        return player;
      }
    }
  }

  update() {
    this.snakes && this.snakes.destroy();
    this.snakes = this.game.add.group();

    this.foods && this.foods.destroy();
    this.foods = this.game.add.group();

    for (const player of this.worldData.players) {
      this.drawSnake(player);
    }

    for (const food of this.worldData.map.foods) {
      this.drawFood(food);
    }

    var currentUser = this.getCurrentUser();

    if (currentUser) {
      var headX = this.game.world.centerX + currentUser.snake.bodyParts[0].center.x;
      var headY = this.game.world.centerY + currentUser.snake.bodyParts[0].center.y;

      this.game.camera.x = headX - (this.game.camera.width / 2);
      this.game.camera.y = headY - (this.game.camera.height / 2);

      this.bg.x = this.game.camera.x;
      this.bg.y = this.game.camera.y;
      this.bg.tilePosition.x = headX * -1;
      this.bg.tilePosition.y = headY * -1;
    }
  }

  drawSnake(player) {
    let first = true;

    const bodyPartGraphics = this.game.add.graphics(this.game.world.centerX, this.game.world.centerY);
    for (const body_part of player.snake.bodyParts) {
      const bodyPartColor = first ? HEAD_COLOR : player.color || BODY_COLOR ;

      bodyPartGraphics.lineStyle(4, 0x000000, 1);
      bodyPartGraphics.drawCircle(
        body_part.center.x,
        body_part.center.y,
        body_part.radius
      );

      bodyPartGraphics.lineStyle(2, bodyPartColor, 1);
      bodyPartGraphics.drawCircle(
        body_part.center.x,
        body_part.center.y,
        body_part.radius
      );

      if (player.snake.destroyed) {
       this.destroySnake(bodyPartGraphics);
      } else {
        this.snakes.add(bodyPartGraphics);

        if (first) {
          first = false;
          this.addPlayerName(player.name, body_part.center.x, body_part.center.y);
        }
      }
    }
  }

  destroySnake(snakeGraphics) {
    this.game.add.tween(snakeGraphics.scale)
        .to( {x: 1.2, y: 1.2}, 1000, Phaser.Easing.Back.InOut, true, 0, false)
        .yoyo(true);

    this.game.add.tween(snakeGraphics)
        .to({alpha: 0}, 1000, 'Linear', true, 0, false);

    setTimeout(snakeGraphics.destroy.bind(snakeGraphics), 1000);
  }

  drawFood(food) {
    const foodGraphics = this.game.add.graphics(this.game.world.centerX, this.game.world.centerY);

    foodGraphics.lineStyle(4, 0x37b714, 1);

    foodGraphics.drawCircle(
      food.circle.center.x,
      food.circle.center.y,
      food.circle.radius
    );

    this.foods.add(foodGraphics);
  }

  addPlayerName(name, x, y) {
    const style = { font: '12px Arial', fill: PLAYER_NAME_COLOR, align: 'center' };

    const text = this.game.add.text(
        0,
        0,
        name,
        style
    );

    text.x = this.game.world.centerX + x - (text.width / 2);
    text.y = this.game.world.centerY + y - 25;

    this.snakes.add(text);
  }
}

module.exports = SnakeArea;
