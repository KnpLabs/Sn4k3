const autobahn = require('autobahn');

console.log("Running AutobahnJS " + autobahn.version);

// the URL of the WAMP Router (Crossbar.io)
//
var wsuri = "ws://127.0.0.1:8080/ws";

// the WAMP connection to the Router
//
var connection = new autobahn.Connection({
    url: wsuri,
    realm: "realm1"
});

// fired when connection is established
connection.onopen = function (session, details) {
    console.log("Connected:", details);
};

// fired when connection is established
connection.onclose = function (reason, details) {
    console.log("Connection lost: " + reason, details);
};

module.exports = connection;
