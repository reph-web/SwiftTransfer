import React, { useState, useEffect } from 'react';

export default function (props) {
    // Init state result
    const [result, setResult] = useState('');
    const [chosenValue, setChosenValue] = useState('');

    // var used define first value to init defaut value
    var isfirst = true;

    // create <li> list rom the response fetched
    function mapResponse(data){
        let contactList = [];

        // create li for each contact
        for(let contact of data){
            if(isfirst){
                setChosenValue(contact.username);
                isfirst = false;
            }
            contactList.push(
                <option value={contact.username} key={contact.username} >
                    @{contact.username}
                </option>
            );
        };
        setResult(contactList);
    }

    // Fetch data from the api
    useEffect(()=>{
        fetch('/api/get-contact')
        .then(response =>{
            return response.json();
        })
        .then((data) =>
        mapResponse(data))
    },[]);

    const onClickSendInvite = ()=>{
        fetch('/api/invite', {
            headers:{
                'Content-Type': 'application/json'
            },
            method : 'POST',
            body: JSON.stringify({
                groupId: props.groupId,
                invitedUser: chosenValue
            })
        })
    }
    // return the HTML of the component
    return(
        <div className='flex flex-nowrap flex-col'>
            <h1 className='mx-auto text-center mb-2'>Choose contact :</h1>
            <div className='flex flex-wrap justify-items-center item-start'>
                <select required className='mx-auto text-xl py-2 px-4 rounded-lg' onChange={e => {setChosenValue(e.target.value)}}>{result}</select>
            </div>
            <button className="bg-purple-500 text-white text-lg px-5 py-1 rounded-lg mt-4 mx-auto" onClick={onClickSendInvite}>Invite</button>
        </div>
    );
}