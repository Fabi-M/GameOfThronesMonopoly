/**
 * @author Selina Stöcklein
 * @date 19.12.2022
 **/
class PlayFieldService {
    #playFields = {};

    static #instance = null;

    /**
     * @author Selina Stöcklein
     */
    constructor() {
        if (PlayFieldService.instance == null) {
            PlayFieldService.instance = this;
            this.setFields();
        } else {
            delete this;
        }
    }

    /**
     * @author Selina Stöcklein
     * @returns {null}
     */
    static getInstance() {
        if (PlayFieldService.#instance == null) {
            PlayFieldService.#instance = new PlayFieldService();
        }
        return PlayFieldService.#instance;
    }

    /**
     * @author Selina Stöcklein
     */
    setFields() {
        // alle figures unter ihrer id als instanz von figure mit $() enthalten in einer liste speichern
        this.#playFields = $('.playerFigure');
    }

    /**
     * @author Selina Stöcklein
     * @param id
     * @returns {*}
     */
    getField(id) {
        return this.#playFields[id];
    }
}