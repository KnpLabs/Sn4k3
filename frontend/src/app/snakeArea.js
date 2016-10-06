const CrossbarConnection = require('./crossbarConnection');

class SnakeArea {
    
    constructor(nodeId) {
        this.canvasDOM = document.getElementById(nodeId);
        this.drawingContext = this.canvasDOM.getContext('2d');
    }

    init() {
        this.canvasDOM.setAttribute('width', 800); //set its width
        this.canvasDOM.setAttribute('height', 800); //and height

        CrossbarConnection.onopen = (session) => {
            session.publish('join', [], {playerName: window.username});

            session.subscribe('tick', (_, data) => {
                console.log('Received tick: ');
                console.log(data);
            })
        };
    }
}

module.exports = SnakeArea;
