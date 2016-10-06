const KEY_LEFT = 37;
const KEY_RIGHT = 39;

class ArrowKeysManager {

    constructor(CrossbarConnection) {

        this.crossbarConnection = CrossbarConnection;
    }

    init() {
        document.onkeyup = this.handleKeyUp.bind(this);
        document.onkeydown = this.handleKeyDown.bind(this);
    }

    handleKeyUp(e) {
        switch (e.keyCode) {
            case KEY_LEFT:

                // Publish on topic "action" to broadcast
                // and tell to others client to draw the circles
                this.crossbarConnection.session.publish('action', [], {
                    username: window.username,
                    direction: 'left',
                    pressed: false
                });

                break;

            case KEY_RIGHT:

                this.crossbarConnection.session.publish('action', [], {
                    username: window.username,
                    direction: 'right',
                    pressed: false
                });

                break;
        }
    }

    handleKeyDown(e) {
        switch (e.keyCode) {
            case KEY_LEFT:

                this.crossbarConnection.session.publish('action', [], {
                    username: window.username,
                    direction: 'right',
                    pressed: true
                });

                break;

            case KEY_RIGHT:

                this.crossbarConnection.session.publish('action', [], {
                    username: window.username,
                    direction: 'right',
                    pressed: true
                });

                break;
        }
    }
}

module.exports = ArrowKeysManager;
