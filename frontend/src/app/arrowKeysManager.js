const KEY_LEFT = 37;
const KEY_RIGHT = 39;

class ArrowKeysManager {

    constructor(CrossbarConnection) {
        this.isHold = false;
        this.crossbarConnection = CrossbarConnection;
    }

    init() {
        document.onkeyup = this.handleKeyUp.bind(this);
        document.onkeydown = this.handleKeyDown.bind(this);
    }

    handleKeyUp(e) {
        if (!this.isHold) {
            return;
        }

        switch (e.keyCode) {
            case KEY_LEFT:
                // Publish on topic "action" to broadcast
                // and tell to others client to draw the circles
                this.crossbarConnection.session.publish('action', [], {
                    playerName: window.playerName,
                    direction: 'left',
                    pressed: false
                });

                break;

            case KEY_RIGHT:

                this.crossbarConnection.session.publish('action', [], {
                    playerName: window.playerName,
                    direction: 'right',
                    pressed: false
                });

                break;
        }

        this.isHold = false;
    }

    handleKeyDown(e) {
        if (this.isHold) {
            return;
        }

        switch (e.keyCode) {
            case KEY_LEFT:
                this.crossbarConnection.session.publish('action', [], {
                    playerName: window.playerName,
                    direction: 'left',
                    pressed: true
                });

                break;

            case KEY_RIGHT:

                this.crossbarConnection.session.publish('action', [], {
                    playerName: window.playerName,
                    direction: 'right',
                    pressed: true
                });

                break;
        }

        this.isHold = true;
    }
}

module.exports = ArrowKeysManager;
