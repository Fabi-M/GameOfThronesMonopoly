/**
 * @author Selina Stöcklein
 * @date 16.12.2022
 **/
class FigureService {

    #playerFigures = null;

    static #instance = null;

    constructor() {
        if (FigureService.instance == null) {
            console.log("singleton");
            FigureService.instance = this;
            this.setFigures();
        } else {
            console.log("nope");
            delete this;
        }
        console.log('FigureService');
    }

    static getInstance() {
        if (FigureService.#instance == null) {
            FigureService.#instance = new FigureService();
        }
        return FigureService.#instance;
    }

    moveFigure(result) {
        var resultObj = JSON.parse(result);
        var playerId=resultObj['activePlayerId'];
        var playFieldId=resultObj['playFieldId']
        console.log('moveFigure')
        console.log(playerId)
        console.log(playFieldId)
        let $figure = this.getFigure(playerId);

    }


    getFigure(playerId) {
        return this.#playerFigures[playerId];
    }

    setFigures() {
        console.log('set figures')
        // alle figures unter ihrer id als instanz von figure mit $() enthalten in einer liste speichern
        let $allFigures = $('.playerFigure');
        $.each($allFigures, key => {
            console.log(key);
        });
    }
}