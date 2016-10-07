// Third-party modules
global.p2 = window.p2 = require('phaser/build/p2');
global.PIXI = window.PIXI = require('phaser/build/pixi');
const Phaser = global.Phaser = require('phaser/build/phaser');
// App modules
const CrossbarConnection = require('./crossbarConnection');
const simpleWorldFixture = require('./fixtures/simple-world');

class SnakeArea {
    
    constructor(nodeId) {
        this.nodeId = nodeId;
    }

    init() {
        this.game = new Phaser.Game(800, 600, Phaser.CANVAS, this.nodeId, {
            preload: this.preload.bind(this),
            create: this.create.bind(this),
            update: this.update.bind(this, simpleWorldFixture),
            render: this.render.bind(this)
        });

        CrossbarConnection.onopen = (session) => {
            session.publish('join', [], {playerName: window.username});

            session.subscribe('tick', (_, data) => {
                console.log('Received tick: ');
                console.log(data);
            })
        };
    }

    preload() {
        this.game.stage.backgroundColor = '#000';
    }

    render() {
        this.game.debug.cameraInfo(this.game.camera, 32, 32);
    }

    create() {
        this.game.world.resize(10000, 10000);

        this.game.camera.y = this.game.world.centerY - (this.game.camera.height / 2);
        this.game.camera.x = this.game.world.centerX - (this.game.camera.width / 2);

        this.game.add.text(600, 800, "- phaser -", {font: "32px Arial", fill: "#330088", align: "center"});
    }

    update(worldData) {
        this.snakes && this.snakes.destroy();
        this.snakes = this.game.add.group();

        for (const player of worldData.players) {
            this.drawSnake(player.snake);
        }

        // tmp test
        for (const bodyPart of worldData.players[0].snake.body_parts) {
            bodyPart.center_point.x++;
            bodyPart.center_point.y++;
        }
    }

    drawSnake(snake) {
        for (const body_part of snake.body_parts) {
            const item = this.game.add.graphics(this.game.world.centerX, this.game.world.centerY);

            item.lineStyle(3, 0xffffff, 1);
            item.drawCircle(
                body_part.center_point.x,
                body_part.center_point.y,
                body_part.radius
            );

            this.snakes.add(item);
        }
    }
}

module.exports = SnakeArea;
