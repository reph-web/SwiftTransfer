import React, { useState } from 'react';
export default function (props) {
    const [read, setRead] = useState(props.read);


    function NotificationContent(){
        let extraButton = '';
        if(props.type === "group"){
            extraButton = <div>
                <button id="accept">Accept Invite</button>
                <button id="decline">Decline Invite</button>
            </div>;
            
            document.querySelector('#accept').addEventListener('click', ()=>{
                fetch('/acceptInvite', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        notificationId: props.id
                    })
                }).catch(error => console.error('Error:', error));
            });

            document.querySelector('#decline').addEventListener('click', ()=>{
                fetch('/declineInvite', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        notificationId: props.id
                    })
                }).catch(error => console.error('Error:', error));

            });          

        }
        return props.content + extraButton;
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
                    <NotificationContent/>
                </div>
            </>)
}