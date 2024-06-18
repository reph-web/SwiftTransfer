import React, { useState, useId} from 'react';

export default function (props) {
    const [value, setValue] = useState(props.value);
    const [modal, setModal] = useState(null);
    const uniqueId = useId();

    function Modal(){

        function closeModal(e){
            // Workaround to make querySelector don't mess with semicolon of useId generated id
                const contentBox = document.querySelector(`[id="${'contentBox' + uniqueId}"]`);
                const modalBtn = document.querySelector(`[id="${'modalOpener' + uniqueId}"]`);
                if(!contentBox.contains(e.target) && !modalBtn.contains(e.target)){
                    setModal(null);
                    this.removeEventListener('click', closeModal);
                }
                
        }
        // Close the modal if clicking outside the modal
        document.addEventListener('click', closeModal);

        function confirmHandler(){
            let newValue = document.querySelector(`[id="${'input' + uniqueId}"]`).value;
            setValue(newValue)

            // Use the api to reflect the new name in db
            fetch('/api/change-group-name', {
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

        function cancelHandler(){
            setModal(null);
            document.removeEventListener('click', closeModal);
        }

        return(
        <div id={'modal' + uniqueId} className = "absolute inset-0 bg-gray-900 bg-opacity-50 h-dvh w-dvh">
            <div id={'contentBox' + uniqueId} className ="flex flex-nowrap justify-between flex-col mx-auto mt-32 w-72 bg-white rounded-xl">
                <div className='text-xl text-center font-semibold mt-4'>Group Name</div>
                <input id={'input' + uniqueId} className='mx-auto my-6 pl-2 text-2xl rounded-lg border-2 border-purple-500' defaultValue={value}/>
                <div className='mx-auto mb-4'>
                    <button id="confirmModalBtn" className="bg-purple-500 text-white text-lg px-5 py-1 rounded-lg mr-4" onClick={confirmHandler}>Confirm</button>
                    <button id="cancelModalBtn" className="bg-red-600 text-white text-lg px-5 py-1 rounded-lg ml-4" onClick={cancelHandler}>Cancel</button>
                </div>
            </div>
        </div>
        )
    }
    // Modal is empty, when button is clicked, modal set to the component and the modal is opening
    return(
        <>
            {modal}
            <span className='text-gray-700 font-semibold text-2xl ml-2'>{value}</span>
            <button className='ml-2' id={'modalOpener' + uniqueId} onClick={()=>{setModal(<Modal/>);}}>⚙️</button>
        </>
    )

}