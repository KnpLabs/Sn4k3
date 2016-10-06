/*
 * 37 - left
 * 38 - up
 * 39 - right
 * 40 - down
 */
class ArrowKeysManager {

    constructor(CrossbarConnection) {
        this.crossbarConnection = CrossbarConnection;
    }

    handleKeyUp() {
        document.onkeyup = function(e) {
            switch (e.keyCode) {
                case 37:

                    // Publish on topic "action" to broadcast
                    // and tell to others client to draw the circles
                    this.crossbarConnection.session.publish('action', {
                        username: window.username,
                        direction: 'left',
                        pressed: false
                    });
                    
                    break;

                case 39:
                    
                    this.crossbarConnection.session.publish('action', {
                        username: window.username,
                        direction: 'right',
                        pressed: false
                    });
                    
                    break;
            }
        };
    }

    handleKeyDown() {
        document.onkeydown = function(e) {
            switch (e.keyCode) {
                case 37:

                    this.crossbarConnection.session.publish('action', {
                        username: window.username,
                        direction: 'right',
                        pressed: true
                    });
                    
                    break;

                case 39:

                    this.crossbarConnection.session.publish('action', {
                        username: window.username,
                        direction: 'right',
                        pressed: true
                    });
                    
                    break;
            }
        };
    }
}

module.exports = ArrowKeysManager;
      