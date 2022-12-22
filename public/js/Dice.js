class Dice {
    constructor() {
        let events = new Events();
        events.addEvent('click', $('.dice'), this.throwDices, {"this": this});
    }

    /**
     * @author Selina Stöcklein & Christian Teubner
     */
    throwDices(event, data) {
        let that = data['this'];
        let action = $('.dice').attr('data-action');
        let url = BASEPATH + '/Roll/' + action;
        let request = null;
        if (action === 'Move') {
            let figureService = FigureService.getInstance();
            console.log(url)
            request = new Ajax(url, false, figureService.moveFigure, data);
        } else if (action === 'Prison') {
            request = new Ajax(url, false, that.displayPopup, data);
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