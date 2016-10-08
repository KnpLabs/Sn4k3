
function makeWorldDataFromNetworkData(lightNetworkData) {
    const worldData = {};
    // Player data
    worldData.players = [];
    for (const playerData of lightNetworkData.p) {
        const newPlayer = {
            name: playerData.n,
            color: playerData.c,
            snake: {
                direction: playerData.s.d,
                headAngle: playerData.s.h,
                length: playerData.s.l,
                destroyed: playerData.s.dd,
            },
        };
        const newPlayerBodyParts = [];
        for (const bodyPart of playerData.s.b) {
            newPlayerBodyParts.push(getCircleFromLightNetworkData(bodyPart));
        }
        newPlayer.snake.bodyParts = newPlayerBodyParts;
        worldData.players.push(newPlayer);
    }
    // Map data (foods and stuff)
    worldData.map = {};
    worldData.map.foods = [];
    for (const foodData of lightNetworkData.m.f) {
        worldData.map.foods.push({
            circle: getCircleFromLightNetworkData(foodData[0]),
            value: foodData[1],
        })
    }
debugger
    return worldData;
}

function getCircleFromLightNetworkData(circle) {
    return {
        center: {x: circle[0][0], y: circle[0][1]},
        radius: circle[1],
    };
}

module.exports.makeWorldDataFromNetworkData = makeWorldDataFromNetworkData;
