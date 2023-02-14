class StartPage {
    constructor() {
        console.log("AJAJAJAJAJAJAJJAJJAWIODNVQWN");
        let events = new Events();
        console.log($('#spielStarten'));
        events.addEvent('click', $('#spielStarten'), this.startGame, {"this": this});
    }

    startGame(){
        console.log("MOOOOOIIINNN");
        let url = BASEPATH + '/StartGame';
        let request = new Ajax(url, false, this.gameStarted, data);
        request.execute();
    }

    gameStarted(){
        console.log("HALLOOOOO");
        //window.location.replace(BASEPATH+"/Play");
    }
}