import {useEffect, useState} from "react";

function MyButton(){
    const [count, setCount] = useState(0)
    const [bgColor, setBgColor] = useState('black')

    const handleClick = () => {
        console.log('我点你一下')
        setCount(count+1)
    }

    useEffect(() => {
        if (count % 2 == 0){
            setBgColor('yellow')
        }else{
            setBgColor('blue')
        }
    }, [count]);

    return (
        <div style={{backgroundColor: bgColor, margin:'10px' }}>
            <div>
                <span>This my btn.</span><br />
                <span>click btn count:{count}.</span><br />
            </div>
            <div>
                <button id='btn' onClick={handleClick}> MY BUTTON.</button>
            </div>

        </div>

    )
}
export default MyButton;