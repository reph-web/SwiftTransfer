import React, { useState } from 'react';

export default function (props) {
    const [i, setI] = useState(props.isInContactList);

    function inviteGroup(props){
        fetch(`/api/invite/${props.groupId}/${props.userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(
                {
                    groupId: props.groupId,
                    userId: props.userId
                }
            )
        })
        .then(response =>  response.json())
        .then(data => {
            if (data.status === 'Contact removed') {
                
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


