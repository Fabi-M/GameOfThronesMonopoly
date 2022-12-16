/**
 * @author Selina Stöcklein
 * @date 16.12.2022
 **/
class FigureService {

    #playerFigures= null;

    static instance = null;

    constructor() {
        if(FigureService.instance == null){
            console.log("singleton");
            FigureService.instance = this;
            this.setFigures();
        }else{
            console.log("nope");
            delete this;
        }
        console.log('FigureService');
    }

    moveFigure(playerId, playFieldId){
        let $figure = this.getFigure(playerId);

    }


    getFigure(playerId) {
        return this.#playerFigures[playerId];
    }

    setFigures() {
        // alle figures unter ihrer id als instanz von figure mit $() enthalten in einer liste speichern
        let $allFigures = $('.playerFigure');
        $.each(typeOptions, key => {
            let typeOption = typeOptions[key]; // array of type option, containing 'name' and 'identifier'
            let optionId = typeOption['identifier'] + 'Option'; // build id of new option

            $('<option>').val(typeOption['identifier']).text(typeOption['name']).attr({ // create new option // add value // add text // add id
                id: optionId
            }).appendTo('#typeSelect'); // append created option to #typeSelect
        });
    }
}