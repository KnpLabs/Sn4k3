// Third-party modules
global.p2 = window.p2 = require('phaser/build/p2');
global.PIXI = window.PIXI = require('phaser/build/pixi');
const Phaser = global.Phaser = require('phaser/build/phaser');

// App modules
const CrossbarConnection = require('./crossbarConnection');
const networkDataTranslator = require('./network-data-translator');

const PLAYER_NAME_COLOR = '#FFFF00';//this is a text color: must be a hex string

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
    this.game.load.image('bg', 'assets/bg.jpg');
    this.game.load.spritesheet('snek', 'assets/snakeswag.png', 32, 32, 21, 2);
    this.game.load.spritesheet('body', 'assets/body.png', 18, 14, 4);
    this.game.load.spritesheet('fruits', 'assets/fruits.png', 60, 60, 12);
  }

  render() {
    this.game.debug.cameraInfo(this.game.camera, 32, 32);
  }

  create() {
    this.game.world.resize(1000000, 1000000);

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

  insideViewZone(x, y, radius) {
    var hx = x,
      hy = y,
      hh = radius,
      hw = radius;

    var cx = this.game.camera.x;
    var cy = this.game.camera.y;
    var ch = this.game.camera.height;
    var cw = this.game.camera.width;

    if (hx < cx + cw &&
      hx + hw > cx &&
      hy < cy + ch &&
      hh + hy > cy) {
      return true;
    }

    return false;
  }

  update() {
    this.snakes && this.snakes.destroy();
    this.snakes = this.game.add.group();

    this.foods && this.foods.destroy();
    this.foods = this.game.add.group();

    this.heads && this.heads.destroy();
    this.heads = this.game.add.group();

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

    this.drawPlayersTable();
  }

  drawSnake(player) {
    let first = true;
    let item;
    let items = [];

    for (const bodyPart of player.snake.bodyParts) {

      if (!this.insideViewZone(
          this.game.world.centerX + bodyPart.center.x,
          this.game.world.centerY + bodyPart.center.y,
          bodyPart.radius
        )) {
        continue;
      }

      if (first) {
        item = this.game.add.sprite(
          this.game.world.centerX + bodyPart.center.x,
          this.game.world.centerY + bodyPart.center.y,
          'snek',
          player.color
        );

        item.angle = 270 - player.snake.headAngle;
      } else {
        item = this.game.add.sprite(
          this.game.world.centerX + bodyPart.center.x,
          this.game.world.centerY + bodyPart.center.y,
          'body',
          player.color
        );
      }

      item.anchor.set(0.5, 0.5);

      if (player.snake.destroyed) {
       this.destroySnake(item);
      } else {
        this.snakes.add(item);
        items.push(item);

        if (first) {
          first = false;
          this.addPlayerName(player.name, player.score, bodyPart.center.x, bodyPart.center.y);
          this.heads.add(item);
          this.heads.bringToTop(item);
        }
      }
    }

    for (item of items.reverse()) {
      this.snakes.bringToTop(item);
    }
  }

  destroySnake(snakeGraphics) {
    this.game.add.tween(snakeGraphics.scale)
      .to( {x: 1.2, y: 1.2}, 1000, Phaser.Easing.Back.InOut, true, 0, false)
      .yoyo(true);

    this.game.add.tween(snakeGraphics)
      .to({alpha: 0}, 1000, 'Linear', true, 0, false);

    let destroy = (item) => {
      item.destroy();
    };

    let destroyCallback = destroy.bind(this, snakeGraphics);

    setTimeout(destroyCallback, 1000);
  }

  drawFood(food) {
    if (!this.insideViewZone(
        this.game.world.centerX + food.circle.center.x,
        this.game.world.centerY + food.circle.center.y,
        food.circle.radius
      )) {
      return;
    }

    const item = this.game.add.sprite(
      this.game.world.centerX + food.circle.center.x,
      this.game.world.centerY + food.circle.center.y,
      'fruits',
      food.type
    );

    item.anchor.set(0.5, 0.5);

    item.scale.x = food.circle.radius / 60;
    item.scale.y = food.circle.radius / 60;

    this.foods.add(item);
  }

  addPlayerName(name, score, x, y) {
    const style = { font: '12px Arial', fill: PLAYER_NAME_COLOR, align: 'center' };

    const text = this.game.add.text(
        0,
        0,
        name + ' - ' + score,
        style
    );

    text.x = this.game.world.centerX + x - (text.width / 2);
    text.y = this.game.world.centerY + y - 25;

    this.snakes.add(text);
    this.snakes.bringToTop(text);
  }

  drawPlayersTable() {
    this.table && this.table.destroy();
    this.table = this.game.add.group();

    var players = this.worldData.players;

    if (players.length === 0) {
      return;
    }

    players.sort((a, b) => {
      return a.score > b.score;
    });

    var data = '';
    const style = { font: '12px Arial', fill: PLAYER_NAME_COLOR, align: 'center' };

    for (const player of players) {
      data += player.name + ' - ' + player.score + "\r\n";
    }

    const text = this.game.add.text(
      this.game.camera.x + this.game.camera.width - 160,
      this.game.camera.y + 30,
      data,
      style
    );

    let container = this.game.add.graphics(
      this.game.camera.position.x + this.game.camera.width,
      this.game.camera.position.y
    );

    container.lineStyle(2, 0xffd900, 1);
    container.beginFill(0xffd900, 0.5);
    container.drawRoundedRect(-170, 20, 150, (players.length * 24) + 20);
    container.endFill();

    this.table.add(container);
    this.table.add(text);
  }
}

module.exports = SnakeArea;
