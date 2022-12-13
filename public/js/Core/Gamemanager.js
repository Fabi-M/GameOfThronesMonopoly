class Gamemanager{

    #currentPlayer = 1;
    #maxPlayer = 4;
    static instance = null;

    constructor() {
        if(Gamemanager.instance == null){
            console.log("singleton");
            Gamemanager.instance = this;
        }else{
            console.log("nope");
            delete this;
        }
        console.log(this.#currentPlayer);
    }

    endTurn(){
        this.#currentPlayer++;
        if(this.#currentPlayer > this.#maxPlayer){
            this.#currentPlayer = 1;
        }
        console.log(this.#currentPlayer);
    }


}