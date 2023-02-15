/**
 * @author Fabian Müller
 */
class StartPage {
    constructor() {
        let events = new Events();
        events.addEvent('click', $('#spielStarten'), this.startGame, {"this": this});
        events.addEvent('click', $('#spiel_laden'), this.loadGame, {"this": this});
    }

    /**
     * Send AJAX to start game
     * @author Fabian Müller
     * @param event
     * @param data
     */
    startGame(event, data){
        let url = BASEPATH + '/StartGame/'+$('#player-count').val();
        let request = new Ajax(url, false, data["this"].gameStarted, data);
        request.execute();
    }

    /**
     * Redirect to play site after game has been started
     * @author Fabian Müller
     * @param data
     */
    gameStarted(data){
        console.log(data);
        console.log("hi");
        let that = this;
        setTimeout(function () {
            StartPage.redirect();
        }, 500);
    }

    static redirect(){
        console.log("bye");
        window.location.replace(BASEPATH+"/Play");
    }

    /**
     * Send AJAX to server to check if game can be loaded
     * @author Fabian Müller
     * @param event
     * @param data
     */
    loadGame(event, data){
        let url = BASEPATH + '/CanLoadGame';
        let request = new Ajax(url, false, data["this"].gameLoaded, data);
        request.execute();
    }

    /**
     * Load game or show error popup
     * @author Fabian Müller
     * @param data
     */
    gameLoaded(data){
        data = JSON.parse(data);
        if(data == false){
            window.alert("Du hast derzeit kein aktives Spiel, bitte starte ein neues");
            // todo Use our awesome looking modal
        }else{
            window.location.replace(BASEPATH+"/Play");
        }
    }
}