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
                alert('Contact removed successfully!');
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
                alert('Contact added successfully!');
                setIsInContactList(true);
            }
        })
        .catch(error => console.error('Error:', error));
        
    };
    
    return (
        <div>
            {isInContactList ? (
                <div>
                    <span>Remove from contact list</span>
                    <button onClick={removeContact}>-</button>
                </div>
            ) : (
                <div>
                    <span>Add to contact list</span>
                    <button onClick={addContact}>+</button>
                </div>
            )}
        </div>
    );
}


