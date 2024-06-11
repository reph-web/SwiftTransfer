import React, { useRef, useState } from 'react';

export default function () {
    // Init state result
    const [result, setResult] = useState('');
    const searchDivRef = useRef(null);

    // Fetch data from the api
    async function callResult(query){
        const url = '/api/searchUpdater/' + query;
        const resp = await fetch(url)
        const parsedResp =  await resp.json();
        const reifiedResp = await JSON.parse(parsedResp);
        return reifiedResp;
    }

    // create <li> list from the response fetched
    function mapResponse(response){
        let completeList = [];
        for( let user of response){
            completeList.push(
            <a href={'/profile/' + user.username}>
                <li className='mt-3 bg-purple-500 rounded-2xl text-white' key={user.username}>
                    <img src={'/build/images/avatar/' + user.avatar} className='inline-block h-6 w-6 rounded-full mx-2 mb-1'/>
                    <span className='font-semibold'>{user.displayedName}
                        <span className='ml-2 font-normal text-md'>@{ user.username }</span>
                    </span>
                </li>
            </a>
            )
        };
        return completeList;
    }

    // Update the result list in on input change
    const onChangeHandler = (e) => {
        // if input empty, doesnt show result
        if(!e.target.value){
            return setResult('');
        }

        // fetch data on change of input value
        callResult(e.target.value)
        .then(response => mapResponse(response))
        .then(mappedResponse => setResult(mappedResponse));
    }
    // return the HTML of the component
    return(
        <div className = 'flex flex-wrap justify-items-center item-start'>
            <input 
                id = 'SearchBar'
                className = ' p-2 mx-auto border-2 border-purple-500 rounded-md w-64 h-8 '
                placeholder='&nbsp;Enter query'
                onChange={onChangeHandler}
            ></input>
            
            <div
                ref = {searchDivRef}
                className='w-2/3 mx-auto self-start'
            >
                <ul>{result}</ul>
            </div>
        </div>
    );
}

