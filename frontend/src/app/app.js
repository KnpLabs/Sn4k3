const $ = require('jquery');
const CrossbarConnection = require('./crossbarConnection');
const SnakeArea = require('./snakeArea');
const ArrowKeysManager = require('./arrowKeysManager');

// now actually open the connection to WAMP Router (Crossbar.io)
//
CrossbarConnection.open();

$(document).ready(() => {
    
    window.username = prompt('Please enter your username');
    
    // Init Area
    var playArea = new SnakeArea('snake-area');
    playArea.init();
    playArea.addFruit();
    
    // Listen arrow keys events
    var arrowKeysManager = new ArrowKeysManager(CrossbarConnection);
    arrowKeysManager.init();
});
