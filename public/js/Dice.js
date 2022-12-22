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
            request = new Ajax(url, false, figureService.moveFigure, {'dice': data['this']});
        } else if (action === 'Prison') {
            request = new Ajax(url, false, that.displayPopup, data);
        }
        request?.execute();
    }

    /**
     * @author Selina Stöcklein
     * @param dice
     */
    toastRolledDice(dice) {
        let toast = new Toast();
        toast.Heading = "Du hast gewürfelt!";
        toast.Body = "<strong>" + dice[0] + "</strong> und <strong>" + dice[1] + "</strong>"
        toast.show();
    }

    /**
     * @author Selina Stöcklein & Christian Teubner
     * @param result
     */
    displayPopup(result) {

        let resultObj = JSON.parse(result);

        alert("Deine gewürfelte Zahlen: \n"
            + resultObj['dice'][0] + " und "
            + resultObj['dice'][1]
            + " Spieler: " + resultObj['activePlayerId']
        );
    }
}