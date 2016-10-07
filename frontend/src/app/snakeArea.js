// Third-party modules
global.p2 = window.p2 = require('phaser/build/p2');
global.PIXI = window.PIXI = require('phaser/build/pixi');
const Phaser = global.Phaser = require('phaser/build/phaser');

// App modules
const CrossbarConnection = require('./crossbarConnection');
const simpleWorldFixture = require('./fixtures/simple-world');

class SnakeArea {

  constructor(nodeId) {
    this.worldData = {players: []};
    this.nodeId = nodeId;
  }

  init() {
    this.game = new Phaser.Game(800, 800, Phaser.CANVAS, this.nodeId, {
      preload: this.preload.bind(this),
      create: this.create.bind(this),
      update: this.update.bind(this),
      render: this.render.bind(this)
    });

    CrossbarConnection.onopen = (session) => {
      session.publish('join', [], {playerName: window.playerName});

      session.subscribe('tick', (_, data) => {
        console.log('Received tick: ');
        console.log(data);
        this.worldData = data;
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
    this.game.world.resize(10000, 10000);

    this.game.camera.y = this.game.world.centerY - (this.game.camera.height / 2);
    this.game.camera.x = this.game.world.centerX - (this.game.camera.width / 2);

    this.bg = this.game.add.tileSprite(
      this.game.camera.x,
      this.game.camera.y,
      this.game.camera.width,
      this.game.camera.height,
      'bg'
    );

    this.cursors = this.game.input.keyboard.createCursorKeys();
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

    for (const player of this.worldData.players) {
      this.drawSnake(player);
    }

    var currentUser = this.getCurrentUser();

    if (currentUser) {
      var headX = this.game.world.centerX + currentUser.snake.body_parts[0].center_point.x;
      var headY = this.game.world.centerY + currentUser.snake.body_parts[0].center_point.y;

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

    for (const body_part of player.snake.body_parts) {
      const item = this.game.add.graphics(this.game.world.centerX, this.game.world.centerY);

      item.lineStyle(4, 0x000000, 1);
      item.drawCircle(
        body_part.center_point.x,
        body_part.center_point.y,
        body_part.radius
      );

      item.lineStyle(2, 0xffffff, 1);
      item.drawCircle(
        body_part.center_point.x,
        body_part.center_point.y,
        body_part.radius
      );

      this.snakes.add(item);

      if (first) {
        first = false;

        var text = player.name;
        var style = { font: "12px Arial", fill: "#000000", align: "center" };

        var t = this.game.add.text(
          0,
          0,
          text,
          style
        );

        t.x = this.game.world.centerX + body_part.center_point.x - (t.width / 2);
        t.y = this.game.world.centerY + body_part.center_point.y - 25;

        this.snakes.add(t);
      }
    }
  }
}

module.exports = SnakeArea;
