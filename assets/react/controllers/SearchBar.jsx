import React from 'react';

export default function () {
    const [result, setResult] = React.useState('');

    async function callResult(query){
        const url = 'http://localhost:9000/searchUpdater/'+ query;
        const resp = await fetch(url)
        const parsedResp =  await resp.json();
        const reifiedResp = await JSON.parse(parsedResp);
        return reifiedResp;
    }

    function mapResponse(response){
        
        return(response.map( username => 
            <li key={username}>
                <a href={`profile/${username}`}>{username}</a>
            </li>));
    }

    const onChangeHandler = (e) => {
        if(!e.target.value){
            return setResult('');
        }
        callResult(e.target.value)
        .then(response => mapResponse(response))
        .then(mappedResponse => setResult(mappedResponse));
        console.log("typed")
    }
    return(
        <div>
            <input 
                className ="border border-black rounded-md" 
                placeholder='Enter query'
                onChange={onChangeHandler}
                ></input>
            <ul>{result}</ul>
        </div>
    );
}

