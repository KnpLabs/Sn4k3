class SnakeArea {
    
    constructor(nodeId) {
        this.canvasDOM = document.getElementById(nodeId);
        this.drawingContext = this.canvasDOM.getContext('2d');
    }

    init() {
        this.canvasDOM.setAttribute('width', 500); //set its width
        this.canvasDOM.setAttribute('height', 500); //and height
    }

    addFruit() {
        this.drawingContext.fillStyle = '#fe57a1'; //hot pink!
        this.drawingContext.fillRect(200, 200, 30, 50); //fill a rectangle (x, y, width, height)
    }
    
}

module.exports = SnakeArea;

      