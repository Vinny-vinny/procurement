class Token {
    isValid(token){
        const payload =this.payload(token);

        if(payload){
            return true;
            //return payload.iss == base_url+"auth/login" || payload.iss == base_url+"auth/signup" ? true : false
        }
        return false;
    }
    payload(token){
       const payload = token.split('.')[1];
        return this.decode(payload);
    }
    decode(payload){
        if(this.isBase64(payload)){
            return JSON.parse(atob(payload));
        }
        return false;

    }
    isBase64(str){
        try{
            return btoa(atob(str)).replace(/=/g,"") == str;
        }
        catch(err){
            return false;
        }
    }
}

export default Token = new Token();
