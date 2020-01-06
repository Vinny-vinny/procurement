class DateConverter{ 
   convertDate(str) {
    var date = new Date(str),
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
    return [date.getFullYear(), mnth, day].join("-");
    }
     conversion(str) {
    var date = new Date(str),
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
    return [day, mnth, date.getFullYear()].join("-");
    }
    addOneYear(str){
    var date = new Date(str);
    date.setFullYear(date.getFullYear()+1);
    }
}

export default DateConverter = new DateConverter();