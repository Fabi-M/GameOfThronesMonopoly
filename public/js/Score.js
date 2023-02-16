/**
 * @author Selina Stöcklein
 * @date 16.02.2023
 **/
class Score {
    constructor() {
        let events = new Events();
        //      events.addEvent('click', $('.diceButton'), this.throwDices, {"this": this});
        this.requestScore();
    }

    requestScore() {
        console.log('request')
        let url = BASEPATH + '/Scores';
        console.log(url)
        let request = new Ajax(url, false, this.displayScore);
        request.execute();
    }

    displayScore(result) {
        console.log(result)
        let popup = new ModalDialog();
        popup.Body = result;
        popup.destroyHeader();
        popup.setBackdrop('static')
        popup.showDialog();
    }
}