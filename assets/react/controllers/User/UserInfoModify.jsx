import React, { useState, useId } from 'react';

export default function (props) {
    const [value, setValue] = useState(props.value);
    const [modal, setModal] = useState(null);
    const uniqueId = useId();

    function Modal() {

        function closeModal(e) {
            const contentBox = document.querySelector(`[id="${'contentBox' + uniqueId}"]`);
            const modalBtn = document.querySelector(`[id="${'modalOpener' + uniqueId}"]`);
            if (!contentBox.contains(e.target) && !modalBtn.contains(e.target)) {
                setModal(null);
                document.removeEventListener('click', closeModal);
            }
        }

        document.addEventListener('click', closeModal);

        function confirmHandler() {
            let newValue = document.querySelector(`[id="${'input' + uniqueId}"]`).value;
            setValue(newValue);

            // Mise à jour de l'API en fonction du type
            const apiEndpoint = props.type === 'Name' 
                ? '/api/change-display-name' 
                : '/api/change-user-bio';
            
            fetch(apiEndpoint, {
                headers: {
                    'Content-Type': 'application/json'
                },
                method: 'PATCH',
                body: JSON.stringify({
                    username: props.username, 
                    value: newValue
                })
            })
            .catch(error => console.error('Error: ', error));

            // Fermer le modal
            setModal(null);
            document.removeEventListener('click', closeModal);
        }

        function cancelHandler() {
            setModal(null);
            document.removeEventListener('click', closeModal);
        }

        let typeInput = null;
        if(props.type == 'Name'){
            typeInput = (
                <input id={'input' + uniqueId} 
                    className='text-2xl rounded-lg border-2 border-purple-500' 
                    defaultValue={value}
                    style={{
                        width: '60%',
                        margin: '2rem 20%'
                    }}
                />
            );
        } else {
            typeInput = (
                <textarea id={'input' + uniqueId} 
                    className='text-xl rounded-lg border-2 border-purple-500' 
                    defaultValue={value}
                    style={{
                        width: '60%',
                        margin: '2rem 20%'
                    }}>
                </textarea>
            );
        }

        return (
            <div id={'modal' + uniqueId} style={{
                backgroundColor: 'rgba(17, 24, 39, 0.5)', 
                zIndex: '50', 
                top: '0', 
                left :'0',
                position: 'fixed',
                height: '100%',
                width: '100%',
                display : 'flex',
                flexWrap: 'nowrap',
                justifyContent: 'center',
                alignItems: 'center'
            }}>
                <div id={'contentBox' + uniqueId} className="rounded-lg bg-white"
                    style ={{
                        width: '30%',
                    }}>
                    <h2 className='text-2xl mt-2 text-center font-semibold'>Modifier {props.type}</h2>
                    {typeInput}
                    <div className='flex justify-center mb-4'>
                        <button id="confirmModalBtn" 
                            className="bg-purple-500 text-white text-lg px-5 py-1 rounded-lg mr-4" 
                            onClick={confirmHandler}>
                            Confirmer
                        </button>
                        <button id="cancelModalBtn" 
                            className="bg-red-600 text-white text-lg px-5 py-1 rounded-lg ml-4" 
                            onClick={cancelHandler}>
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <>
            {modal}
            <span className={`text-gray-700 ${props.type === 'Name' ? 'font-semibold text-2xl' : 'text-lg font-regular'} ml-2`}>
                {value}
            </span>
            <button className='ml-2' 
                id={'modalOpener' + uniqueId} 
                onClick={() => { setModal(<Modal />); }}>
                ⚙️
            </button>
        </>
    );
}