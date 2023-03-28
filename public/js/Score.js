/**
 * @author Selina Stöcklein
 * @date 16.02.2023
 **/
class Score {
    constructor() {
        //this.requestScore();
    }

    requestScore() {
        let url = BASEPATH + '/Scores';
        let request = new Ajax(url, false, this.displayScore, this);
        request.execute();
    }

    displayScore(result, that) {
        let popup = new ModalDialog();
        popup.Body = result;
        popup.destroyHeader();
        popup.SuccessFunction  = that.redirectAndKill;
        popup.SuccessFunctionName = 'Spiel beenden';
        popup.SuccessFunctionOptions=that;
        popup.enableYesNoButton();
        popup.setBackdrop('static');
        popup.showDialog();
    }

    redirectAndKill(that) {
        console.log('kill')
        let url = BASEPATH + '/EndGame';
        console.log(this)
        let request = new Ajax(url, false, that.redirect, that);
        request.execute();
    }

    redirect(result) {
        console.log('redirect')
        console.log(result)
        window.location.href = BASEPATH + '/Homepage';
    }
}