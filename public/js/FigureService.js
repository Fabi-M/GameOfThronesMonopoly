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
        $("#next_player").prop("disabled", false);
        let resultObj = JSON.parse(result);
        console.log("IMPORTANT______________________________");
        console.log(resultObj);
        if(resultObj['comment'] !== undefined){
            data['dice'].toastSpecialField(resultObj['comment']);
            $('#currentMoney').text(resultObj['money']);
        }
        if (resultObj['dice'][0] !== resultObj['dice'][1]) {
            $(".diceButton").prop("disabled", true);
        }
        console.log(resultObj);
        let me = FigureService.getInstance();
        data['dice'].toastRolledDice(resultObj['dice']); // dice.js
        let playerId = resultObj['activePlayerId'];
        let playFieldId = resultObj['playFieldId'];
        let figure = me.getFigure(playerId);
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