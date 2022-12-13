class Gamemanager{

    #currentPlayer = 1;
    #maxPlayer = 4;

    constructor() {
        console.log(this.#currentPlayer);
    }

    endTurn(){
        this.#currentPlayer++;
        if(this.#currentPlayer > this.#maxPlayer){
            this.#currentPlayer = 1;
        }
    }


}