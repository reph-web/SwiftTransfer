import React, { useState } from 'react';

export default function (props) {

    function inviteGroup(){
        fetch(`/api/invite`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(
                {
                    groupId: props.groupId,
                    username: props.username
                }
            )
        })
        .catch(error => console.error('Error:', error));
    };

    document.querySelector('invite').addEventListener('click', inviteGroup);
    return (
        <button id='invite 'class='bg-purple-500 text-white px-5 py-1 rounded-lg ml-4'>
            Invite
        </button>
    );
}


