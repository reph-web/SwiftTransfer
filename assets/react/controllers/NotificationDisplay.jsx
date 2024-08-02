import React, { useState } from 'react';

export default function Notification(props) {
    const [isVisible, setIsVisible] = useState(true);

    const IfGroupNotificationContent = () => {
        if (props.type === "group" && isVisible) {
            return (
                <div className="mt-2">
                    <button className="py-2 px-4 mr-2 border border-transparent rounded-md shadow-sm font-medium text-white bg-purple-950 hover:bg-purple-700 hover:border-purple-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                        onClick={() => handleAcceptClick(props.id)}>Accept Invite</button>
                    <button className="py-2 px-4 border border-transparent rounded-md shadow-sm font-medium text-white bg-purple-950 hover:bg-purple-700 hover:border-purple-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                        onClick={() => handleDeclineClick(props.id)}>Decline Invite</button>
                </div>
            );
        }
        return null;
    }

    const handleAcceptClick = (notificationId) => {
        fetch('/api/accept-invite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notificationId })
        })
        .then(() => setIsVisible(false))
        .catch(error => console.error('Error:', error));
    }

    const handleDeclineClick = (notificationId) => {
        fetch('/api/decline-invite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notificationId })
        })
        .then(() => setIsVisible(false))
        .catch(error => console.error('Error:', error));
    }

    const toggleHandler = (id) => {
        let notification = document.querySelector(`div[notificationId="${id}"]`);
        notification.classList.toggle('hidden');
    }

    if (!isVisible) {
        return null;
    }

    return (
        <>
            <div className='inline-flex mx-2'>
                <h1 className='underline'>{props.type}</h1>
                <button
                    onClick={() => toggleHandler(props.id)}
                    className='ml-1 mt-1'
                >
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6b7280">
                        <path d="m480-360 160-160H320l160 160Zm0 280q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/>
                    </svg>
                </button>
            </div>

            <div notificationid={props.id} className='hidden ml-8 mb-4'>
                {props.content}
                <IfGroupNotificationContent />
            </div>
        </>
    );
}