import React, { useState, useId } from 'react';

export default function (props) {
    const [value, setValue] = useState(props.value);
    const [modal, setModal] = useState(null);
    const uniqueId = useId();

    function Modal() {

        function closeModal(e) {
            // Workaround to make querySelector don't mess with semicolon of useId generated id
            const contentBox = document.querySelector(`[id="${'contentBox' + uniqueId}"]`);
            const modalBtn = document.querySelector(`[id="${'modalOpener' + uniqueId}"]`);
            if (!contentBox.contains(e.target) && !modalBtn.contains(e.target)) {
                setModal(null);
                document.removeEventListener('click', closeModal);
            }
        }
        // Close the modal if clicking outside the modal
        document.addEventListener('click', closeModal);

        function confirmHandler() {
            let newValue = document.querySelector(`[id="${'input' + uniqueId}"]`).value;
            setValue(newValue);

            // Use the api to reflect the new name in db, if type is name, calling changing group
            // name api, else changing description
            fetch(props.type == 'name' ? '/api/change-group-name' : '/api/change-group-description', {
                headers: {
                    'Content-Type': 'application/json'
                },
                method: 'PATCH',
                body: JSON.stringify({
                    groupId: props.id,
                    name: newValue
                })
            })
                .then(response => response.json())
                .then(data => console.log(data))
                .catch(error => console.error('Error:', error));

            // Close the modal
            setModal(null);
            document.removeEventListener('click', closeModal);
        }

        function cancelHandler() {
            setModal(null);
            document.removeEventListener('click', closeModal);
        }
        let typeInput = null;
        if(props.type == 'Name'){
            typeInput = <input id={'input' + uniqueId} className=' text-2xl rounded-lg border-2 border-purple-500' defaultValue={value}
                style={{
                    width: '60%',
                    margin: '2rem 20%'
                }}
            />
        }else{
            typeInput = <textarea id={'input' + uniqueId} className=' text-2xl rounded-lg border-2 border-purple-500' defaultValue={value}
            style={{
                width: '60%',
                margin: '2rem 20%'
            }}></textarea>
            }

        return (
            // Removed some TailwindCSS classes because it DIDN'T work on thoses particular elements, but style do
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
                    <h2 className='text-2xl mt-2 text-center font-semibold'>Group {props.type}</h2>

                    {typeInput}
                    
                    <div className='flex justify-center mb-4'>
                        <button id="confirmModalBtn" className="bg-purple-500 text-white text-lg px-5 py-1 rounded-lg mr-4" onClick={confirmHandler}>Confirm</button>
                        <button id="cancelModalBtn" className="bg-red-600 text-white text-lg px-5 py-1 rounded-lg ml-4" onClick={cancelHandler}>Cancel</button>
                    </div>
                </div>
            </div>
        );
    }

    // Modal is empty, when button is clicked, modal set to the component and the modal is opening
    return (
        <>
            {modal}
            <span className={`text-gray-700 ${props.type === 'Name' ? 'font-semibold text-2xl' : 'text-lg font-regular'} ml-2`}>{value}</span>
            <button className='ml-2' id={'modalOpener' + uniqueId} onClick={() => { setModal(<Modal />); }}>⚙️</button>
        </>
    );
}