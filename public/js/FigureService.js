/**
 * @author Selina Stöcklein
 * @date 16.12.2022
 **/
class FigureService {

    #playerFigures = {};

    static #instance = null;

    /**
     * @author Selina Stöcklein
     */
    constructor() {
        if (FigureService.instance == null) {
            FigureService.instance = this;
            this.setFigures();
        } else {
            delete this;
        }
    }

    /**
     * @author Selina Stöcklein
     */
    static getInstance() {
        if (FigureService.#instance == null) {
            FigureService.#instance = new FigureService();
        }
        return FigureService.#instance;
    }

    /**
     * move the figure
     * @author Selina Stöcklein
     */
    moveFigure(result, data) {
        $(".diceButton").prop("disabled", true);
        let resultObj = JSON.parse(result);
        if(resultObj['comment'] !== undefined){
            data['dice'].toastSpecialField(resultObj['comment']);
        }

        console.log(resultObj);
        let me = FigureService.getInstance();
        data['dice'].toastRolledDice(resultObj['dice']); // dice.js
        let playerId = resultObj['activePlayerId'];
        let playFieldId = resultObj['playFieldId'];
        let figure = me.getFigure(playerId);
        let playerFigure=figure.getElement();

        if (resultObj['goToJail']!==0){
            let $jail = $('#spieler-bereich-10');
            playerFigure.appendTo($jail);
            let toast = new Toast();
            toast.Heading = 'Du wurdest in das Gefängnis teleportiert!';
            toast.AllowToastClose = true;
            toast.HideAfter = false;
            toast.Loader = false;
            toast.BgColor = '#57031e';
            toast.show();
            $('#next_player').prop("disabled", false);
            return;
        }

        let isNotPasch = resultObj['dice'][0] !== resultObj['dice'][1]
        console.log(isNotPasch)
        figure.move(playFieldId, isNotPasch);
        figure.payRent(playFieldId);

    }


    /**
     * @author Selina Stöcklein
     */
    getFigure(playerId) {
        return this.#playerFigures[playerId];
    }

    /**
     * @author Selina Stöcklein
     */
    setFigures() {
        // alle figures unter ihrer id als instanz von figure mit $() enthalten in einer liste speichern
        for (let inGameId = 1; inGameId <= 4; inGameId++) {
            this.#playerFigures[inGameId] = new Figure($('#figure' + inGameId));
        }
    }
}