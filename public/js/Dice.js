class Dice {
    constructor() {
        let events = new Events();
        events.addEvent('click', $('.diceButton'), this.throwDices, {"this": this});
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
        this.showRollDice(dice);
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

    /**
     * Add default class to dices and call function to show result
     * @author Fabian Müller
     * @source https://lenadesign.org/2020/06/18/roll-the-dice/
     * @param result array with 2 ints (dice results)
     */
    showRollDice(result) {
        let d1       = document.getElementById('dice1');
        let d2       = document.getElementById('dice2');

        d1.classList.add("show-default");
        d2.classList.add("show-default");

        let that = this;
        setTimeout(function () {
            that.showDiceResults(result);
        }, 500);
    }

    /**
     * Shows the result of the dice throw
     * @author Fabian Müller
     * @source https://lenadesign.org/2020/06/18/roll-the-dice/
     * @param result array with 2 ints (dice results)
     */
    showDiceResults(result){
        let d1       = document.getElementById('dice1');
        let d2       = document.getElementById('dice2');

        for (let i = 1; i <= 6; i++) {
            d1.classList.remove('show-' + i);
            if (result[0] === i) {
                d1.classList.add('show-' + i);
            }
        }

        for (let k = 1; k <= 6; k++) {
            d2.classList.remove('show-' + k);
            if (result[1] === k) {
                d2.classList.add('show-' + k);
            }
        }

        d1.classList.remove("show-default");
        d2.classList.remove("show-default");
    }
}