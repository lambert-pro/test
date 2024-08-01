function MyClick(){
  function handleClick(){
    console.log('my click')
  }
  return (
    <button onClick={handleClick}>
      click me
    </button>
  )

}

export default MyClick;