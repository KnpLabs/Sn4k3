var express = require('express');
var http = require('http');
var app = express();
var server = http.createServer(app);

const PORT = 8080;

// Handle server errors
server.on('error', function (e) {
    if (false && e.code === 'EADDRINUSE') {
        // Specific message when port already in use.
        console.error(
            'Port '+PORT+' already in use. Select another one ' +
            'or check that another instance of the server is not running.')
        ;
    } else {
        // Show all errors in logs.
        console.error('An error has occured while running the server.');
        for (var i in e) { if (e.hasOwnProperty(i)) { console.error(i, e[i]); } }
    }
});

// Serve only static files
app.use(express.static('public'));

// Listen to specified port.
server.listen(PORT, function () {
    console.log('App is listening to port '+PORT);
});
