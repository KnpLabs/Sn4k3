module.exports = {
    players: [
        {
            name: 'Ivan',
            snake: {
                direction: 'left',
                head_angle: 30,
                length: 3,
                body_parts: [
                    {
                        center_point: {
                            x: 10,
                            y: 10
                        },
                        radius: 10
                    },
                    {
                        center_point: {
                            x: 25,
                            y: 25
                        },
                        radius: 10
                    },
                    {
                        center_point: {
                            x: 40,
                            y: 40
                        },
                        radius: 10
                    }
                ]
            },
            score: 100
        }
    ]
};
