import React, { useState } from 'react';

export default function (props) {
    const [isInContactList, setIsInContactList] = useState(props.isInContactList);


    function removeContact(){
        fetch('/api/remove-contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ contactUsername: props.username })
        })
        .then(response =>  response.json())
        .then(data => {
            if (data.status === 'Contact removed') {
                setIsInContactList(false);
            }
        })
        .catch(error => console.error('Error:', error));
    };
    
    function addContact(){
        fetch('/api/add-contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ contactUsername: props.username })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'Contact added') {
                setIsInContactList(true);
            }
        })
        .catch(error => console.error('Error:', error));
        
    };
    
    return (
        <div class="flex place-content-center space-x-4 mx-auto">
            {isInContactList ? (
                    <button class="bg-purple-600 text-white px-4 py-2 rounded-full" onClick={removeContact}>Remove from contact list</button>
            ) : (
                    <button class="bg-purple-600 text-white px-4 py-2 rounded-full" onClick={addContact}>Add to Contact List</button>
            )}
        </div>
    );
}


