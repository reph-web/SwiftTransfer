import React, { useState, useEffect, useId } from 'react';

export default function (props) {
    // Init state result
    const [result, setResult] = useState('');
    const [chosenValue, setChosenValue] = useState('');
    const [modalDisplay, setModalDisplay] = useState(null);
    const uniqueId = useId();

    // var used to define the first value to init default value
    let isFirst = true;

    // create <li> list from the response fetched
    function mapResponse(data){
        let contactList = [];

        // create li for each contact
        for(let contact of data){
            if(isFirst){
                setChosenValue(contact.username);
                isFirst = false;
            }
            contactList.push(
                <option value={contact.username} key={contact.username} >
                    @{contact.username}
                </option>
            );
        };
        setResult(contactList);
    }

    // create modal component
    function Modal({ textToDisplay, onClose }) {
        useEffect(() => {
            function closeModal(e) {
                const contentBox = document.querySelector(`[id="contentBox${uniqueId}"]`);
                if (contentBox && !contentBox.contains(e.target)) {
                    onClose();
                    document.removeEventListener('click', closeModal);
                }
            }
            document.addEventListener('click', closeModal);
            return () => document.removeEventListener('click', closeModal);
        }, [onClose]);

        return (
            <div id={`modal${uniqueId}`} style={{
                backgroundColor: 'rgba(17, 24, 39, 0.5)', 
                zIndex: '50', 
                top: '0', 
                left: '0',
                position: 'fixed',
                height: '100%',
                width: '100%',
                display: 'flex',
                flexWrap: 'nowrap',
                justifyContent: 'center',
                alignItems: 'center'
            }}>
                <div id={`contentBox${uniqueId}`} className="rounded-lg bg-white"
                    style={{
                        width: '30%',
                    }}>
                    <h2 className='text-2xl mt-2 text-center font-semibold'>{textToDisplay}</h2>
                    <div className='flex justify-center mb-4'>
                        <button
                            className="text-white text-xl rounded-lg mt-4 cursor-pointer" 
                            onClick={onClose}
                            // bg-color-500 and px don't work so :
                            style={{
                                backgroundColor: '#22c55e',
                                paddingLeft: '1.5rem',
                                paddingRight: '1.5rem'

                            }}
                            >
                            Ok
                        </button>
                    </div>
                </div>
            </div>
        );
    }

    // Fetch data from the api
    useEffect(() => {
        fetch('/api/get-contact')
            .then(response => response.json())
            .then(data => mapResponse(data));
    }, []);

    const onClickSendInvite = () => {
        fetch('/api/invite', {
            headers: {
                'Content-Type': 'application/json'
            },
            method: 'POST',
            body: JSON.stringify({
                groupId: props.groupId,
                invitedUser: chosenValue
            })
        })
        .then(() => setModalDisplay(<Modal textToDisplay={`${chosenValue} invited to your group`} onClose={() => setModalDisplay(null)} />));
    }

    // return the HTML of the component
    return (
        <>
            {modalDisplay}
            <div className='flex flex-nowrap flex-col'>
                <h1 className='mx-auto text-center mb-2'>Choose contact :</h1>
                <div className='flex flex-wrap justify-items-center item-start'>
                    <select required className='mx-auto text-xl py-2 px-4 rounded-lg' onChange={e => { setChosenValue(e.target.value) }}>{result}</select>
                </div>
                <button className="bg-purple-500 text-white text-lg px-5 py-1 rounded-lg mt-4 mx-auto" onClick={onClickSendInvite}>Invite</button>
            </div>
        </>
    );
}
