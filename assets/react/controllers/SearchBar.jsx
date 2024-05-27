import React, { useRef, useState } from 'react';

export default function () {
    // Init state result
    const [result, setResult] = useState('');
    const searchDropdownRef = useRef(null);

    // Fetch data from the api
    async function callResult(query){
        const url = 'http://localhost:9000/api/searchUpdater/' + query;
        const resp = await fetch(url)
        const parsedResp =  await resp.json();
        const reifiedResp = await JSON.parse(parsedResp);
        return reifiedResp;
    }

    // Autocomplete input when clicking on a result
    const onClickChoiceHandler = (e) => {
        document.querySelector('#SearchBar').value = e.target.innerText;
        searchDropdownRef.current.classList.add('hidden');
    }

    // create <li> list from the response fetched
    function mapResponse(response){
        return(response.map( username => 
            <li key={username} onClick={onClickChoiceHandler}>
                {username}
            </li>));
    }

    // Update the result list in on input change
    const onChangeHandler = (e) => {
        // if input empty, doesnt show result
        if(!e.target.value){
            return setResult('');
        }

        // open the dropdown if typing in input
        if(searchDropdownRef.current){
            searchDropdownRef.current.classList.remove('hidden');
        }

        // fetch data on change of input value
        callResult(e.target.value)
        .then(response => mapResponse(response))
        .then(mappedResponse => setResult(mappedResponse));
    }

    document.addEventListener('click', (e) => {
        if(!searchDropdownRef.current.contains(e.target)){
            searchDropdownRef.current.classList.add('hidden');
        }
    });
    // return the HTML of the component
    return(
        <div>
            <input 
                id = 'SearchBar'
                className ="border border-black rounded-md" 
                placeholder='Enter query'
                onChange={onChangeHandler}
            ></input>
            
            <div
                className = "absolute bg-white rounded-md shadow-lg cursor-pointer hidden"
                ref = {searchDropdownRef}
            >
                <ul>{result}</ul>
            </div>
        </div>
    );
}

