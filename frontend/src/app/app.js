const $ = require('jquery');
const CrossbarConnection = require('./crossbarConnection');
const SnakeArea = require('./snakeArea');
const ArrowKeysManager = require('./arrowKeysManager');

// now actually open the connection to WAMP Router (Crossbar.io)
//
CrossbarConnection.open();

$(document).ready(() => {
    try {
        if (!window.localStorage.getItem('playerName')) {
            window.playerName = prompt('Please enter your username: ');
            window.localStorage.setItem('playerName', window.playerName);
        } else {
            window.playerName = window.localStorage.getItem('playerName');
        }

        // Init Area
        var playArea = new SnakeArea('snake-area');
        playArea.init();

        // Listen arrow keys events
        var arrowKeysManager = new ArrowKeysManager(CrossbarConnection);
        arrowKeysManager.init();
    } catch (e) {
        console.log(e);
    }
});
