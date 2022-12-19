class Dice {
    constructor() {
        let events = new Events();
        events.addEvent('click', $('#wuerfeln'), this.throwDices, {"this": this});
    }

    /**
     * @author Selina Stöcklein & Christian Teubner
     */
    throwDices(event, data) {
        let that = data['this'];
        //TODO 15.12.2022 Selina: soll je nach action Move oder Prison an URL anhängen "data-action='Move'"
        //TODO 15.12.2022 Selina: Alle buttons die wie Dice interagieren sollen brauchen die Klasse "dice"
        let action = $('.dice').attr('data-action');
        let url = BASEPATH + '/Roll/' + action;
        let request = null;
        if (action === 'Move') {
            let figureService = FigureService.getInstance();
            console.log(figureService)
            let request = new Ajax(url, false, figureService.moveFigure, data);
        } else if (action === 'Prison') {
            let request = new Ajax(url, false, that.displayPopup, data);
        }
        console.log(action);
        request?.execute();
    }

    /**
     * @author Selina Stöcklein & Christian Teubner
     * @param result
     */
    displayPopup(result) {
        var resultObj = JSON.parse(result);

        alert("Deine gewürfelte Zahlen: \n"
            + resultObj['dice'][0] + " und "
            + resultObj['dice'][1]
            + " Spieler: " + resultObj['activePlayerId']
        );
    }
}