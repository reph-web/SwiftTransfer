import React from 'react';

export default function (props) {
    // Function to handle the deletion of a member
    function handleDelete() {
        fetch(`/api/delete-group`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                groupId: props.id,
            })
        })
        .then(response => {
            if (!response.ok) {
                console.log(response)
            }
            return response.json();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    return (
        <button 
            className="bg-red-600 text-white px-3 py-1 rounded-lg"
            onClick={handleDelete}
        >
            Delete Group
        </button>
    );
}