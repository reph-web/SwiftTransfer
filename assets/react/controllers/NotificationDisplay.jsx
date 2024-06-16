import React, { useState } from 'react';



export default function (props) {

    function NotificationContent(){
        let extraButton = '';
        if(props.type === "group"){
            extraButton = <div>
                <a href="{{ path('app_acceptInvite', {'user': app.user, 'group' : n.ifGroupId}) }}"></a>
                <a href="{{ path('app_declineInvite', {'notifId': n.id}) }}"></a>
            </div>;
        }
        return props.content + extraButton;
    }
    
    function toggleHandler(id){
        let notification = document.querySelector(`div[notificationId="${id}"]`);
        notification.classList.toggle('hidden');
    }
    return(<>
                <div>
                    {/* For capitalizing the type */}
                    <h1>{props.type}</h1>
                    <button
                        onClick={() => toggleHandler(props.id)}
                    >˅</button>
                </div>

                <div notificationId={props.id} class='hidden'>
                    <NotificationContent/>
                </div>
            </>)
}