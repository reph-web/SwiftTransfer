import React, { useState } from 'react';

export default function (props) {    
    const [read, setRead] = useState(props.read);

    function IfGroupNotificationContent(){
        let extraButton = null;
        if(props.type === "group"){
            extraButton = <div>
                <button className="py-2 px-4 border border-transparent rounded-md shadow-sm font-medium text-white bg-purple-950 hover:bg-purple-700 hover:border-purple-950 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                    onClick={() => handleAcceptClick(props.id)}>Accept Invite</button>
                <button  className="py-2 px-4 border border-transparent rounded-md shadow-sm font-medium text-white bg-purple-950 hover:bg-purple-700"
                    onClick={() => handleDeclineClick(props.id)}>Decline Invite</button>
            </div>;
        }
        return extraButton;
    }

    function handleAcceptClick(notificationId) {
        fetch('/accept-invite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notificationId })
        }).catch(error => console.error('Error:', error));
    }

    function handleDeclineClick(notificationId) {
        fetch('/decline-invite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notificationId })
        }).catch(error => console.error('Error:', error));
    }
    
    function toggleHandler(id){
        let notification = document.querySelector(`div[notificationId="${id}"]`);
        notification.classList.toggle('hidden');
    }
    
    function reflectCheckboxState(){

        read ? setRead(false) : setRead(true);

        fetch('/api/notification-read-change', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                notificationId: props.id,
                state: read
            })
        })
        .catch(error => console.error('Error:', error));
    }

    return(<>
                <div>
                    <input readid={props.id} value={read} onClick={reflectCheckboxState} type='checkbox'/>
                    {/* For capitalizing the type */}
                    <h1>{props.type}</h1>
                    <button
                        onClick={() => toggleHandler(props.id)}
                    >˅</button>
                </div>

                <div notificationid={props.id} className='hidden'>
                    {props.content}
                    <IfGroupNotificationContent/>
                </div>
            </>)
}