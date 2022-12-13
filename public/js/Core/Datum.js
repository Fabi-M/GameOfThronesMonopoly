/**
 *  class for getting date
 */
class Datum{

    constructor() {
        this.today = new Date();
    }

    /** get the current date
     *
     * @param separator // like '.' or '/'
     * @return {*}
     */
    getDate(separator) {
        let day = this.today.getDate();
        let month = this.today.getMonth() + 1; // months start counting by zero
        let year = this.today.getFullYear();

        if (day < 10) {
            day = '0' + day;
        }

        if (month < 10) {
            month = '0' + month;
        }

       // return day + separator + month + separator + year;
        return year + separator + month + separator + day;
    }

    getTime(){
        return this.today.getHours() + ':' + this.today.getMinutes() + ':' + this.today.getSeconds();
    }

}