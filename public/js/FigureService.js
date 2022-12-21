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
     * @author Selina Stöcklein
     */
    moveFigure(result) {
        $("#next_player").prop( "disabled", false );
        $("#wuerfeln").prop( "disabled", true );
        console.log(result)
        let resultObj = JSON.parse(result);
        let playerId = resultObj['activePlayerId'];
        let playFieldId = resultObj['playFieldId'];

        let figure = FigureService.getInstance().getFigure(playerId);
        figure.move(playFieldId);
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
        let $allFigures = $('.playerFigure');
        let playerId = 1;
        $.each($allFigures, key => {
            this.#playerFigures[playerId] = new Figure($allFigures[key]);
            playerId++
        });
    }
}