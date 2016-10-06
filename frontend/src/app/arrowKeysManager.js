/*
 * 37 - left
 * 38 - up
 * 39 - right
 * 40 - down
 */
class ArrowKeysManager {

    constructor(CrossbarConnection) {
        this.KEY_LEFT = 37;
        this.KEY_RIGHT = 39;
        this.crossbarConnection = CrossbarConnection;
    }

    init() {
        document.onkeyup = this.handleKeyUp.bind(this);
        document.onkeydown = this.handleKeyDown.bind(this);
    }

    handleKeyUp(e) {
        switch (e.keyCode) {
            case this.KEY_LEFT:

                // Publish on topic "action" to broadcast
                // and tell to others client to draw the circles
                this.crossbarConnection.session.publish('action', {
                    username: window.username,
                    direction: 'left',
                    pressed: false
                });

                break;

            case this.KEY_RIGHT:

                this.crossbarConnection.session.publish('action', {
                    username: window.username,
                    direction: 'right',
                    pressed: false
                });

                break;
        }
    }

    handleKeyDown(e) {
        switch (e.keyCode) {
            case this.KEY_LEFT:

                this.crossbarConnection.session.publish('action', {
                    username: window.username,
                    direction: 'right',
                    pressed: true
                });

                break;

            case this.KEY_RIGHT:

                this.crossbarConnection.session.publish('action', {
                    username: window.username,
                    direction: 'right',
                    pressed: true
                });

                break;
        }
    }
}

module.exports = ArrowKeysManager;
